<?php

namespace Bibo\Mvc\Core\Session;

use Bibo\Mvc\Core\Crypto\Crypto;
use Bibo\Mvc\Core\Exception\Custom\Crypto\DecryptException;
use Bibo\Mvc\Core\Exception\Custom\Crypto\EncryptException;
use Bibo\Mvc\Core\Traits\CompressAwareTrait;
use Bibo\Mvc\Core\Traits\EncryptedAwareTrait;
use Random\RandomException;
use SessionHandlerInterface;

/**
 * Class EncryptedSessionHandler
 *
 * This class implements the SessionHandlerInterface to provide encrypted session storage.
 * It uses OpenSSL encryption to secure session data before storing it to the filesystem.
 *
 * @package Biboletin\Session
 */
class EncryptedSessionHandler implements SessionHandlerInterface
{
    use EncryptedAwareTrait;
    use CompressAwareTrait;

    private Crypto $crypto;
    /**
     * The directory path where session files will be stored
     *
     * @var string
     */
    private string $savePath;

    /**
     * The encryption method used for session data
     */
    public function __construct(Crypto $crypto)
    {
        $this->crypto = $crypto;
    }

    /**
     * Close the session
     *
     * This method is called when the session is closed. In this implementation,
     * no special action is needed, so it always returns true.
     *
     * @return bool Always returns true
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * Destroy a session
     *
     * Removes the session file associated with the given session ID.
     *
     * @param string $id The session ID
     *
     * @return bool True if the session was successfully destroyed or didn't exist, false otherwise
     */
    public function destroy(string $id): bool
    {
        $file = $this->savePath . '/sess_' . $id . '.session';

        return !file_exists($file) || unlink($file);
    }

    /**
     * Garbage collection
     *
     * Removes expired session files based on the maximum lifetime.
     *
     * @param int $max_lifetime The maximum lifetime of session files in seconds
     *
     * @return int|false The number of deleted session files or false on failure
     */
    public function gc(int $max_lifetime): int|false
    {
        $files = glob($this->savePath . '/sess_*');
        foreach ($files as $file) {
            if (filemtime($file) + $max_lifetime < time()) {
                unlink($file);
            }
        }

        return true;
    }

    /**
     * Open the session
     *
     * Initializes the session storage path and creates the directory if it doesn't exist.
     *
     * @param string $path The path where session files will be stored
     * @param string $name The session name
     *
     * @return bool True if the path is writable, false otherwise
     */
    public function open(string $path, string $name): bool
    {
        $this->savePath = $path;

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        return is_writable($path);
    }

    /**
     * Read session data
     *
     * Reads and decrypts the session data for the given session ID.
     *
     * @param string $id The session ID
     *
     * @return string|false The decrypted session data or an empty string if the session doesn't exist,
     *                      or false on failure
     * @throws DecryptException
     */
    public function read(string $id): string|false
    {
        $file = $this->savePath . 'sess_' . $id;

        if (!file_exists($file)) {
            return '';
        }

        $data = file_get_contents($file);

        if ($this->isEncrypted()) {
            $data = $this->crypto->decrypt($data);
        }

        if ($this->isCompressed()) {
            $data = gzuncompress($data);
        }

        return $data;
    }

    /**
     * Write session data
     *
     * Encrypts and writes the session data to the storage.
     *
     * @param string $id   The session ID
     * @param string $data The session data to write
     *
     * @return bool True on success, false on failure
     */
    public function write(string $id, string $data): bool
    {
        $file = $this->savePath . 'sess_' . $id;

        if ($this->isCompressed()) {
            $data = gzcompress($data, 9);
        }

        try {
            $value = $this->isEncrypted() ? $this->crypto->encrypt($data) : $data;

            return file_put_contents($file, $value) !== false;
        } catch (RandomException | EncryptException $e) {
            return false;
        }
    }
}
