<?php

namespace Bibo\Mvc\Core\Cookie;

use Bibo\Mvc\Core\Crypto\Crypto;
use Bibo\Mvc\Core\Exception\Custom\Crypto\DecryptException;
use Bibo\Mvc\Core\Exception\Custom\Crypto\EncryptException;
use Bibo\Mvc\Core\Traits\EncryptedAwareTrait;
use Bibo\Mvc\Core\Traits\NameAwareTrait;
use Bibo\Mvc\Core\Traits\PrefixAwareTrait;
use Random\RandomException;

/**
 * Cookie class for managing HTTP cookies.
 *
 * This class provides methods to set and retrieve cookie properties,
 * and to send cookies to the browser.
 *
 * @package Biboletin\CookieHandler
 */
class CookieHandler
{
    use NameAwareTrait;
    use EncryptedAwareTrait;
    use PrefixAwareTrait;

    /**
     * Value of the cookie.
     *
     * @var string $cookieValue
     */
    private string $cookieValue = '';

    /**
     * Expiration time of the cookie in seconds.
     *
     * @var int $cookieExpire
     */
    private int $cookieExpire;

    /**
     * Path on the server in which the cookie will be available.
     *
     * @var string $cookiePath
     */
    private string $cookiePath;

    /**
     * Domain that the cookie is available to.
     *
     * @var string $cookieDomain
     */
    private string $cookieDomain;

    /**
     * Indicates whether the cookie should only be transmitted over a secure HTTPS connection.
     *
     * @var bool $cookieSecure
     */
    private bool $cookieSecure;

    /**
     * Indicates whether the cookie is accessible only through the HTTP protocol.
     *
     * @var bool $cookieHttpOnly
     */
    private bool $cookieHttpOnly;

    /**
     * Indicates the SameSite attribute of the cookie.
     *
     * @var string $cookieSameSite
     */
    private string $cookieSameSite;

    /**
     * Indicates whether the cookie should be sent as a raw value.
     *
     * @var bool $cookieRaw
     */
    private bool $cookieRaw;

    /**
     * Indicates whether the cookie should be forced to be set.
     *
     * @var bool $cookieForce
     */
    private bool $cookieForce;

    /**
     * Indicates whether the cookie should only be set if the connection is secure.
     *
     * @var bool $cookieSecureOnly
     */
    private bool $cookieSecureOnly;

    /**
     * Partitioned cookie flag
     *
     * @var bool
     */
    private bool $partitioned;

    /**
     * Crypto instance for encryption and decryption.
     *
     * @var Crypto
     */
    private Crypto $crypto;

    /**
     * Constructor for the Cookie class.
     */
    public function __construct()
    {
    }

    public function setCrypto(Crypto $crypto): void
    {
        $this->crypto = $crypto;
    }

    /**
     * Set the value of the cookie.
     *
     * @param string $cookieValue
     *
     * @return CookieHandler
     *
     * @throws EncryptException | RandomException
     */
    public function setValue(string $cookieValue): self
    {
        $value = $this->isEncrypted()
            ? $this->crypto->encrypt($cookieValue)
            : $cookieValue;

        $this->cookieValue = $value;

        return $this;
    }

    /**
     * Set the expiration time of the cookie.
     *
     * @param int $cookieExpire
     *
     * @return $this
     */
    public function setExpire(int $cookieExpire): self
    {
        $this->cookieExpire = (time() + $cookieExpire);
        return $this;
    }

    /**
     * Set the path on the server where the cookie will be available.
     *
     * @param string $cookiePath
     *
     * @return $this
     */
    public function setPath(string $cookiePath): self
    {
        $this->cookiePath = $cookiePath;
        return $this;
    }

    /**
     * Set the domain that the cookie is available to.
     *
     * @param string $cookieDomain
     *
     * @return $this
     */
    public function setDomain(string $cookieDomain): self
    {
        $this->cookieDomain = $cookieDomain;
        return $this;
    }

    /**
     * Set whether the cookie should only be transmitted over a secure HTTPS connection.
     *
     * @param bool $cookieSecure
     *
     * @return $this
     */
    public function setSecure(bool $cookieSecure): self
    {
        $this->cookieSecure = $cookieSecure;
        return $this;
    }

    /**
     * Set whether the cookie is accessible only through the HTTP protocol.
     *
     * @param bool $cookieHttpOnly
     *
     * @return $this
     */
    public function setHttpOnly(bool $cookieHttpOnly): self
    {
        $this->cookieHttpOnly = $cookieHttpOnly;
        return $this;
    }

    /**
     * Set the SameSite attribute of the cookie.
     *
     * @param string $cookieSameSite
     *
     * @return $this
     */
    public function setSameSite(string $cookieSameSite): self
    {
        $this->cookieSameSite = $cookieSameSite;
        return $this;
    }

    /**
     * Set whether the cookie should be sent as a raw value.
     *
     * @param bool $cookieRaw
     *
     * @return $this
     */
    public function setRaw(bool $cookieRaw): self
    {
        $this->cookieRaw = $cookieRaw;
        return $this;
    }

    /**
     * Set whether the cookie should be forced to be set.
     *
     * @param bool $cookieForce
     *
     * @return $this
     */
    public function setForce(bool $cookieForce): self
    {
        $this->cookieForce = $cookieForce;
        return $this;
    }

    /**
     * Set whether the cookie should only be set if the connection is secure.
     *
     * @param bool $cookieSecureOnly
     *
     * @return $this
     */
    public function setSecureOnly(bool $cookieSecureOnly): self
    {
        $this->cookieSecureOnly = $cookieSecureOnly;
        return $this;
    }

    /**
     * Set whether the cookie should only be set as a raw value.
     *
     * @param bool $cookieRawOnly
     *
     * @return $this
     */
    public function setRawOnly(bool $cookieRawOnly): self
    {
        $this->cookieRaw = $cookieRawOnly;
        return $this;
    }

    /**
     * Set whether the cookie should only be set if it is forced.
     *
     * @param bool $cookieForceOnly
     *
     * @return $this
     */
    public function setForceOnly(bool $cookieForceOnly): self
    {
        $this->cookieForce = $cookieForceOnly;
        return $this;
    }

    /**
     * Set whether the cookie should be partitioned.
     *
     * @param bool $partitioned
     *
     * @return $this
     */
    public function setPartitioned(bool $partitioned = true): self
    {
        $this->partitioned = $partitioned;
        return $this;
    }

    /**
     * Get the value of the cookie.
     *
     * @return string
     * @throws DecryptException
     */
    public function getValue(): string
    {
        return $this->isEncrypted()
            ? $this->crypto->decrypt($this->cookieValue)
            : $this->cookieValue;
    }

    /**
     * Get the expiration time of the cookie.
     *
     * @return int
     */
    public function getExpire(): int
    {
        return $this->cookieExpire;
    }

    /**
     * Get the path on the server where the cookie will be available.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->cookiePath;
    }

    /**
     * Get the domain that the cookie is available to.
     *
     * @return string
     */
    public function getDomain(): string
    {
        return $this->cookieDomain;
    }

    /**
     * Get whether the cookie should only be transmitted over a secure HTTPS connection.
     *
     * @return bool
     */
    public function getSecure(): bool
    {
        return $this->cookieSecure;
    }

    /**
     * Get whether the cookie is accessible only through the HTTP protocol.
     *
     * @return bool
     */
    public function getHttpOnly(): bool
    {
        return $this->cookieHttpOnly;
    }

    /**
     * Get the SameSite attribute of the cookie.
     *
     * @return string
     */
    public function getSameSite(): string
    {
        return $this->cookieSameSite;
    }

    /**
     * Get whether the cookie should be sent as a raw value.
     *
     * @return bool
     */
    public function getRaw(): bool
    {
        return $this->cookieRaw;
    }

    /**
     * Get whether the cookie should be forced to be set.
     *
     * @return bool
     */
    public function getForce(): bool
    {
        return $this->cookieForce;
    }

    /**
     * Get whether the cookie should only be set if the connection is secure.
     *
     * @return bool
     */
    public function getSecureOnly(): bool
    {
        return $this->cookieSecureOnly;
    }

    /**
     * Get whether the cookie should only be set as a raw value.
     *
     * @return bool
     */
    public function getRawOnly(): bool
    {
        return $this->cookieRaw;
    }

    /**
     * Get whether the cookie should only be set if it is forced.
     *
     * @return bool
     */
    public function getForceOnly(): bool
    {
        return $this->cookieForce;
    }

    /**
     * Get whether the cookie should be partitioned.
     *
     * @return bool
     */
    public function getPartitioned(): bool
    {
        return $this->partitioned;
    }

    public function isExpired(): bool
    {
        return $this->cookieExpire < time();
    }

    /**
     * Send the cookie to the browser.
     *
     * @return bool Returns true on success, false on failure.
     * @throws DecryptException
     */
    public function send(): bool
    {
        $value = $this->getValue();
        $cookieName = $this->getName();
        $expiresAt = $this->getExpire();

        if ($this->isEncrypted()) {
            try {
                $value = $this->crypto->encrypt($value);
            } catch (EncryptException | RandomException $e) {
                return false;
            }
        }

        if ($this->getPartitioned()) {
            $cookie = sprintf(
                '%s=%s; Expires=%s; Path=%s; Domain=%s; Secure=%s; HttpOnly=%s; SameSite=%s; Partitioned',
                rawurlencode($cookieName),
                rawurlencode($value),
                $expiresAt,
                $this->getPath(),
                $this->getDomain(),
                $this->getSecure() ? 'true' : 'false',
                $this->getHttpOnly() ? 'true' : 'false',
                $this->getSameSite()
            );

            header('Set-Cookie: ' . $cookie, false);
            return true;
        }

        return setcookie(
            $cookieName,
            $value,
            [
                'expires' => $expiresAt,
                'path' => $this->getPath(),
                'domain' => $this->getDomain(),
                'secure' => $this->getSecure(),
                'httponly' => $this->getHttpOnly(),
                'samesite' => $this->getSameSite(),
            ]
        );
    }

    /**
     * Set the cookie with the specified name and value.
     *
     * @param string $name  The name of the cookie.
     * @param mixed  $value The value of the cookie.
     *
     * @return bool Returns true on success, false on failure.
     *
     * @throws DecryptException
     */
    public function set(string $name, mixed $value): bool
    {
        $this->setName($name);
        try {
            $this->setValue($value);
        } catch (EncryptException | RandomException $e) {
            return false;
        }

        return $this->send();
    }

    /**
     * Retrieve the value of the cookie.
     *
     * @return bool Returns the cookie value if set, null otherwise.
     */
    public function destroy(): bool
    {
        if (isset($_COOKIE[$this->getName()])) {
            unset($_COOKIE[$this->getName()]);
        }
        return setcookie(
            $this->getName(),
            '',
            [
                'expires' => time() - 3600,
                'path' => $this->getPath(),
                'domain' => $this->getDomain(),
                'secure' => $this->getSecure(),
                'httponly' => $this->getHttpOnly(),
                'samesite' => $this->getSameSite(),
            ]
        );
    }

    /**
     * Retrieve the value of a cookie by its name.
     *
     * @param string $name The name of the cookie to retrieve.
     *
     * @return mixed Returns the cookie value if set, null otherwise.
     */
    public function get(string $name): mixed
    {
        if (isset($_COOKIE[$name])) {
            if ($this->getEncrypted()) {
                try {
                    return $this->crypto->decrypt($_COOKIE[$name]);
                } catch (EncryptException | RandomException | DecryptException $e) {
                    // Return null if decryption fails
                    return null;
                }
            }
            return $_COOKIE[$name];
        }
        return null;
    }

    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Create a CookieHandler instance from an array of cookie data.
     *
     * @param array<string, mixed> $data An associative array containing cookie properties.
     *
     * @return self Returns a new instance of CookieHandler with the specified properties.
     *
     * @throws EncryptException | RandomException
     */
    public function fromArray(array $data): self
    {
        $cookie = clone $this;
        $cookie->setName($data['name'])
            ->setValue($data['value'] ?? '')
            ->setExpire($data['expire'] ?? 0)
            ->setPath($data['path'] ?? '/')
            ->setDomain($data['domain'] ?? '')
            ->setSecure($data['secure'] ?? false)
            ->setHttpOnly($data['httpOnly'] ?? false)
            ->setSameSite($data['sameSiteValue'] ?? '')
            ->setPartitioned($data['partitioned'] ?? false)
            ->setEncrypted($data['encrypted'] ?? false);
        return $cookie;
    }

    /**
     * Convert the cookie properties to an associative array.
     *
     * @return array<string, mixed> Returns an associative array containing the cookie properties.
     * @throws DecryptException
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'value' => $this->getValue(),
            'expire' => $this->getExpire(),
            'path' => $this->getPath(),
            'domain' => $this->getDomain(),
            'secure' => $this->getSecure(),
            'httponly' => $this->getHttpOnly(),
            'samesite' => $this->getSameSite(),
            // 'raw' => $this->getRaw(),
            // 'force' => $this->getForce(),
            'secure_only' => $this->getSecure(),
        ];
    }

    /**
     * Destructor for the Cookie class.
     *
     * This method is called when the object is destroyed.
     */
    public function __destruct()
    {
    }
}
