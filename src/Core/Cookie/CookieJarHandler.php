<?php

namespace Bibo\Mvc\Core\Cookie;

use Bibo\Mvc\Core\Crypto\Crypto;
use Bibo\Mvc\Core\Exception\Custom\Crypto\EncryptException;
use Bibo\Mvc\Core\Traits\EncryptedAwareTrait;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use Random\RandomException;

/**
 * Cookie Jar Handler
 */
class CookieJarHandler
{
    use EncryptedAwareTrait;

    /**
     * Cookies
     *
     * @var array
     */
    protected array $cookies = [];

    protected CookieHandler $cookie;

    /**
     * Crypto
     *
     * @var Crypto
     */
    protected Crypto $crypto;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Crypto
     *
     * @param Crypto $crypto
     *
     * @return void
     */
    public function setCrypto(Crypto $crypto): void
    {
        $this->crypto = $crypto;
    }

    /**
     * Cookie
     *
     * @param CookieHandler $cookie
     *
     * @return void
     */
    public function setCookie(CookieHandler $cookie): void
    {
        $this->cookie = $cookie;
    }

    public function getCookie(): CookieHandler
    {
        return $this->cookie;
    }

    public function add(CookieHandler $cookie): void
    {
        $this->cookies[$cookie->getName()] = $cookie;
    }

    public function get(string $name): ?CookieHandler
    {
        $cookie = $this->cookies[$name] ?? null;

        if ($cookie && $cookie->isExpired()) {
            $this->remove($name);

            return null;
        }

        if ($cookie && $this->isEncrypted()) {
            $cookie->decrypt();
        }

        return $cookie;
    }

    public function all(): array
    {
        return array_map(function ($cookie) {
            if ($cookie->isExpired()) {
                $this->remove($cookie->getName());
            }
            return $cookie;
        }, $this->cookies);
    }

    public function has(string $name): bool
    {
        return isset($this->cookies[$name]) && !$this->cookies[$name]->isExpired();
    }

    public function remove(string $name): void
    {
        unset($this->cookies[$name]);
    }

    public function clear(): void
    {
        $this->cookies = [];
    }

    public function toHeader(): string
    {
        $parts = [];
        foreach ($this->all() as $cookie) {
            if (!$cookie->isExpired()) {
                $parts[] = $cookie->toHeader();
            }
        }

        return 'Cookie: ' . implode('; ', $parts);
    }

    public function toCurlHeader(): string
    {
        $parts = [];
        foreach ($this->all() as $cookie) {
            if (!$cookie->isExpired()) {
                $parts[] = rawurlencode($cookie->getName()) . '=' . rawurlencode($cookie->getValue());
            }
        }

        return implode('; ', $parts);
    }

    /**
     * @throws RandomException
     * @throws EncryptException
     */
    public function parseSetCookieHeader(array $setCookieHeaders, bool $decrypt = false): void
    {
        foreach ($setCookieHeaders as $header) {
            $parts = explode(';', $header);
            $nameValue = explode('=', array_shift($parts), 2);
            $name = trim($nameValue[0]);
            $value = isset($nameValue[1]) ? trim($nameValue[1]) : '';

            if ($decrypt) {
                try {
                    $value = $this->crypto->decrypt($value) ?? '';
                } catch (Exception $e) {
                    // Handle decryption failure, possibly log it
                    continue;
                }
            }

            $cookie = new CookieHandler();
            $cookie->setName($name)
                ->setValue($value);

            foreach ($parts as $part) {
                [$partName, $partValue] = array_map('trim', explode('=', $part, 2));

                switch (strtolower($partName)) {
                    case 'expires':
                        $cookie->setExpire(strtotime($partValue));
                        break;
                    case 'path':
                        $cookie->setPath($partValue);
                        break;
                    case 'domain':
                        $cookie->setDomain($partValue);
                        break;
                    case 'secure':
                        $cookie->setSecure(true);
                        break;
                    case 'httponly':
                        $cookie->setHttpOnly(true);
                        break;
                    case 'samesite':
                        $cookie->setSameSite($partValue);
                        break;
                    case 'raw':
                        $cookie->setRaw(true);
                        break;
                    case 'force':
                        $cookie->setForce(true);
                        break;
                    case 'secureonly':
                        $cookie->setSecureOnly(true);
                        break;
                }
            }
            $this->add($cookie);
        }
    }

    public function saveToCurlFile(string $cookieFilePath): bool
    {
        $lines = [
            '# Netscape HTTP Cookie File',
            '# This file was generated by CookieJarHandler',
            '# https://curl.se/docs/http-cookies.html',
            ''
        ];

        foreach ($this->all() as $cookie) {
            $domain = $cookie->getDomain() ?: 'localhost';
            $includeSubdomains = str_starts_with($domain, '.') ? 'true' : 'false';
            $path = $cookie->getPath() ?: '/';
            $secure = $cookie->getSecure() ? 'true' : 'false';
            $expires = $cookie->getExpire() ?? 2145916800; // default: 2038
            $name = $cookie->getName();
            $value = $cookie->getValue();

            $lines[] = implode("\t", [
                $domain,
                $includeSubdomains,
                $path,
                $secure,
                $expires,
                $name,
                $value
            ]);
        }

        return file_put_contents($cookieFilePath, implode(PHP_EOL, $lines)) !== false;
    }

    public function saveToFile(string $path): bool
    {
        $data = array_map(fn ($cookie) => $cookie->toArray(), $this->all());

        return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) !== false;
    }

    /**
     * @throws EncryptException
     * @throws RandomException
     */
    public function loadFromFile(string $path): bool
    {
        if (!file_exists($path)) {
            return false;
        }

        $content = file_get_contents($path) ?: '';
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }

        foreach ($data as $cookieData) {
            $cookie = $this->getCookie();
            $cookie->fromArray($cookieData);
            $this->add($cookie);
        }

        return true;
    }

    public function toArray(): array
    {
        return array_map(fn ($cookie) => $cookie->toArray(), $this->all());
    }

    /**
     * @throws RandomException
     * @throws EncryptException
     */
    public function loadFromArray(array $data): void
    {
        foreach ($data as $cookieData) {
            $cookie = new CookieHandler();
            $cookie->fromArray($cookieData);
            $this->add($cookie);
        }
    }

    public function send(): void
    {
        foreach ($this->all() as $cookie) {
            setcookie(
                $cookie->getName(),
                $cookie->getValue(),
                [
                    'expires' => $cookie->getExpire(),
                    'path' => $cookie->getPath(),
                    'domain' => $cookie->getDomain(),
                    'secure' => $cookie->getSecure(),
                    'httponly' => $cookie->getHttpOnly(),
                    'samesite' => $cookie->getSameSite() ?? 'Lax',
                ]
            );
        }
    }

    /**
     * @throws RandomException
     * @throws EncryptException
     */
    public function parseFromGlobals(bool $decrypt = false): void
    {
        foreach ($_COOKIE as $name => $value) {
            if ($decrypt) {
                try {
                    $value = $this->crypto->decrypt($value) ?? '';
                } catch (Exception $e) {
                    continue; // skip if decryption fails
                }
            }

            $cookie = new CookieHandler();
            $cookie->setName($name)->setValue($value);

            $this->add($cookie);
        }
    }

    /**
     * @throws RandomException
     * @throws EncryptException
     */
    public function parseFromRequest(ServerRequestInterface $request, bool $decrypt = false): void
    {
        foreach ($request->getCookieParams() as $name => $value) {
            if ($decrypt) {
                try {
                    $value = $this->crypto->decrypt($value) ?? '';
                } catch (Exception $e) {
                    continue;
                }
            }

            $cookie = new CookieHandler();
            $cookie->setName($name)->setValue($value);

            $this->add($cookie);
        }
    }

    public function getSetCookieHeaders(): array
    {
        $headers = [];
        foreach ($this->all() as $cookie) {
            $headers[] = $cookie->toHeader();
        }
        return $headers;
    }
}
