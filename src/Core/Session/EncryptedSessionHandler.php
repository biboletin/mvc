<?php

declare(strict_types=1);

namespace Bibo\Mvc\Core\Session;

use Bibo\Mvc\Core\Crypto\Crypto;
use Bibo\Mvc\Core\Exception\Custom\Crypto\DecryptException;
use Bibo\Mvc\Core\Exception\Custom\Crypto\EncryptException;
use Bibo\Mvc\Core\Traits\CompressAwareTrait;
use Bibo\Mvc\Core\Traits\EncryptedAwareTrait;
use Random\RandomException;
use SessionHandlerInterface;

/**
 * EncryptedSessionHandler
 *
 * A robust, production-ready session save handler that stores session data in files
 * with optional compression and encryption. This implementation focuses on safety
 * (file locking, atomic writes), predictability (consistent file naming), and
 * resilience (catching and recovering from corrupt session data).
 *
 * Behaviour highlights:
 *  - Uses a single, consistent filename scheme: <savePath>/sess_<id>.session
 *  - Performs exclusive locks on writes and shared locks on reads to avoid races
 *  - Writes atomically via temporary file + rename
 *  - Returns empty string on read errors (per PHP session handler contract)
 *  - Deletes corrupt sessions when decryption/uncompression fails
 *  - Validates session id format to prevent path traversal attacks
 *
 * @package Bibo\Mvc\Core\Session
 */
class EncryptedSessionHandler implements SessionHandlerInterface
{
    use EncryptedAwareTrait;
    use CompressAwareTrait;

    /**
     * Underlying crypto service used for encrypt/decrypt operations.
     *
     * @var Crypto
     */
    private Crypto $crypto;

    /**
     * Absolute save path where session files are written. Always has no trailing slash.
     *
     * @var string
     */
    private string $savePath = '';

    /**
     * Allowed session id pattern (alphanumeric, dash, comma). Prevents traversal.
     */
    private const string ID_PATTERN = '/^[a-zA-Z0-9,-]+$/';

    /**
     * File suffix for session files.
     */
    private const string FILE_SUFFIX = '.session';

    /**
     * Set the crypto service used for encrypt/decrypt operations.
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
     * Ensure the path exists and is writable. Save path is normalized to remove
     * trailing directory separator.
     *
     * @param string $path Session save path provided by PHP (or your wrapper)
     * @param string $name Session name (unused, kept for signature compatibility)
     *
     * @return bool True if path is writable and ready, false otherwise.
     */
    public function open(string $path, string $name): bool
    {
        $normalized = rtrim($path, '\\/');

        if ($normalized === '') {
            return false;
        }

        if (!is_dir($normalized)) {
            if (!@mkdir($normalized, 0775, true) && !is_dir($normalized)) {
                return false;
            }
        }

        if (!is_writable($normalized)) {
            // Try to relax permissions if possible
            @chmod($normalized, 0775);
            if (!is_writable($normalized)) {
                return false;
            }
        }

        $this->savePath = $normalized;

        return true;
    }

    /**
     * No-op here but kept for completeness.
     *
     * @return bool Always true
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * Read session payload from disk, decrypt and uncompress if configured.
     * On any failure we return an empty string (per PHP contract) and attempt
     * to remove obviously corrupt files.
     *
     * @param string $id Session id
     *
     * @return string Decoded session data or empty string on failure.
     */
    public function read(string $id): string
    {
        if (!$this->isValidId($id)) {
            return '';
        }

        $file = $this->getFilePath($id);

        if (!is_file($file)) {
            return '';
        }

        $fp = @fopen($file, 'rb');
        if ($fp === false) {
            return '';
        }

        // Acquire shared lock for read
        if (!flock($fp, LOCK_SH)) {
            fclose($fp);
            return '';
        }

        $data = stream_get_contents($fp);
        // release lock & close
        flock($fp, LOCK_UN);
        fclose($fp);

        if ($data === false || $data === '') {
            return '';
        }

        try {
            // Decrypt first (if enabled), then uncompress
            if ($this->isEncrypted()) {
                $data = $this->crypto->decrypt($data);
            }

            if ($this->isCompressed()) {
                $uncompressed = @gzuncompress($data);
                if ($uncompressed === false) {
                    throw new \RuntimeException('Failed to uncompress session data');
                }
                $data = $uncompressed;
            }
        } catch (DecryptException | \Throwable $e) {
            // Corrupt session — remove it and return empty string to avoid fatal
            @unlink($file);
            return '';
        }

        return is_string($data) ? $data : '';
    }

    /**
     * Write session payload to disk. The write is performed atomically by
     * writing to a temporary file and renaming to final location. Exclusive
     * lock is obtained while writing.
     *
     * @param string $id Session id
     * @param string $data Serialized session data
     *
     * @return bool True on success, false on failure
     *
     * @throws RandomException
     */
    public function write(string $id, string $data): bool
    {
        if (!$this->isValidId($id)) {
            return false;
        }

        $file = $this->getFilePath($id);
        $dir = dirname($file);

        if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
            return false;
        }

        // Compress then encrypt (if configured)
        $payload = $data;

        if ($this->isCompressed()) {
            $payload = gzcompress($payload, 9);
            if ($payload === false) {
                return false;
            }
        }

        if ($this->isEncrypted()) {
            try {
                $payload = $this->crypto->encrypt($payload);
            } catch (RandomException | EncryptException $e) {
                return false;
            }
        }

        // Write atomically to temp file then rename
        $tmp = $file . '.' . bin2hex(random_bytes(6)) . '.tmp';

        $written = @file_put_contents($tmp, $payload, LOCK_EX);
        if ($written === false) {
            @unlink($tmp);
            return false;
        }

        // Set secure permissions for session files; ignore failures
        @chmod($tmp, 0664);

        // Atomic replace
        if (!@rename($tmp, $file)) {
            @unlink($tmp);
            return false;
        }

        return true;
    }

    /**
     * Remove the session file associated with the given id.
     *
     * @param string $id Session id
     *
     * @return bool True on success (or when file doesn't exist), false on failure
     */
    public function destroy(string $id): bool
    {
        if (!$this->isValidId($id)) {
            return true; // nothing to do
        }

        $file = $this->getFilePath($id);

        if (!is_file($file)) {
            return true;
        }

        return @unlink($file) || !is_file($file);
    }

    /**
     * Garbage collect old session files. Returns number of deleted files.
     *
     * @param int $max_lifetime Maximum lifetime in seconds
     *
     * @return int|false Number of deleted files, or false on failure
     */
    public function gc(int $max_lifetime): int|false
    {
        if ($this->savePath === '') {
            return 0;
        }

        $pattern = $this->savePath . '/sess_*' . self::FILE_SUFFIX;
        $files = glob($pattern) ?: [];

        $deleted = 0;
        $now = time();

        foreach ($files as $file) {
            if (!is_file($file)) {
                continue;
            }

            $mtime = @filemtime($file);
            if ($mtime === false) {
                continue;
            }

            if ($mtime + $max_lifetime < $now) {
                if (@unlink($file)) {
                    $deleted++;
                }
            }
        }

        return $deleted;
    }

    /**
     * Produce a consistent file path for a session id.
     *
     * @param string $id Session id
     *
     * @return string Absolute file path
     */
    private function getFilePath(string $id): string
    {
        return $this->savePath . '/sess_' . $id . self::FILE_SUFFIX;
    }

    /**
     * Validate session id to minimize security risks (path traversal etc.).
     *
     * @param string $id
     *
     * @return bool
     */
    private function isValidId(string $id): bool
    {
        return (bool) preg_match(self::ID_PATTERN, $id);
    }
}
