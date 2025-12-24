<?php

namespace Bibo\Mvc\Core\Crypto;

use Bibo\Mvc\Core\Enums\CryptoVersion;
use Bibo\Mvc\Core\Enums\HashAlgorithm;
use Bibo\Mvc\Core\Exception\Custom\Crypto\DecryptException;
use Bibo\Mvc\Core\Exception\Custom\Crypto\EncryptException;
use InvalidArgumentException;
use Random\RandomException;

/**
 * Class Crypto
 *
 * Provides methods for encrypting and decrypting data using a specified cipher algorithm.
 * Supports AES-256-GCM encryption with a random initialization vector (IV) and authentication tag.
 */
class Crypto
{
    /**
     * The encryption key derived from the provided key and salt.
     *
     * @var string
     */
    private string $key;

    /**
     * The cipher algorithm used for encryption and decryption.
     *
     * @var string
     */
    private string $cipherAlgorithm;

    /**
     * The length of the initialization vector (IV) in bytes.
     *
     * @var int
     */
    private int $ivLength;

    /**
     * The salt used for key derivation.
     *
     * @var int
     */
    private const int SALT_LENGTH = 16;

    /**
     * The length of the HMAC key used for integrity verification.
     * This is set to 32 bytes, which is standard for SHA-256 HMAC.
     *
     * @var int
     */
    private const int HMAC_LENGTH = 32;

    /**
     * The length of the authentication tag for GCM mode.
     * This is set to 16 bytes, which is standard for AES-256-GCM.
     *
     * @var int
     */
    private const int TAG_LENGTH = 16;

    private bool $useHmac;

    /**
     * Getters for the properties.
     */
    /**
     * Returns the encryption key.
     * This key is used for deriving the actual encryption key
     * used in the encryption and decryption processes.
     * It is important to keep this key secure and not expose it publicly.
     *
     * @return string The encryption key.
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Returns the cipher algorithm used for encryption and decryption.
     * This algorithm defines the method of encryption, such as AES-256-GCM.
     * It is crucial to ensure that the cipher algorithm is supported by the OpenSSL library.
     *
     * @return string The cipher algorithm.
     */
    public function getCipherAlgorithm(): string
    {
        return $this->cipherAlgorithm;
    }

    /**
     * Returns the length of the initialization vector (IV) in bytes.
     * The IV is used to ensure that the same plaintext encrypted multiple times
     * will produce different ciphertexts, enhancing security.
     * The length is determined by the cipher algorithm used.
     *
     * @return int The length of the IV in bytes.
     */
    public function getIvLength(): int
    {
        return $this->ivLength;
    }

    /**
     * Returns whether HMAC is used for integrity verification.
     * If true, an HMAC is calculated and appended to the encrypted data
     * to ensure that the data has not been tampered with.
     * This adds an additional layer of security to the encryption process.
     *
     * @return bool True if HMAC is used, false otherwise.
     */
    public function isUseHmac(): bool
    {
        return $this->useHmac;
    }

    /**
     * Setters for the properties.
     */
    /**
     * Sets the encryption key.
     * This key is used for deriving the actual encryption key
     * used in the encryption and decryption processes.
     * It is important to keep this key secure and not expose it publicly.
     *
     * @param string $key The encryption key to set.
     *
     * @throws InvalidArgumentException If the key is empty or invalid.
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * Sets the cipher algorithm to be used for encryption and decryption.
     * This method validates that the provided cipher algorithm is supported by OpenSSL.
     *
     * @param string $cipherAlgorithm The cipher algorithm to set.
     *
     * @throws InvalidArgumentException If the cipher algorithm is not supported.
     */
    public function setCipherAlgorithm(string $cipherAlgorithm): void
    {
        $this->cipherAlgorithm = trim(strtolower($cipherAlgorithm));

        if (!in_array($this->cipherAlgorithm, openssl_get_cipher_methods(true))) {
            throw new InvalidArgumentException(
                'Invalid cipher algorithm provided: ' . $this->cipherAlgorithm
            );
        }

        $this->ivLength = openssl_cipher_iv_length($this->cipherAlgorithm);
    }

    /**
     * Sets the length of the initialization vector (IV) in bytes.
     * This method allows customization of the IV length, which is used in the encryption process.
     *
     * @param int $ivLength The length of the IV in bytes.
     *
     * @throws InvalidArgumentException If the IV length is not valid for the cipher algorithm.
     */
    public function setIvLength(int $ivLength): void
    {
        $this->ivLength = $ivLength;
    }

    /**
     * Sets whether to use HMAC for integrity verification.
     * If true, an HMAC is calculated and appended to the encrypted data
     * to ensure that the data has not been tampered with.
     * This adds an additional layer of security to the encryption process.
     *
     * @param bool $useHmac True to use HMAC, false otherwise.
     */
    public function setUseHmac(bool $useHmac): void
    {
        $this->useHmac = $useHmac;
    }

    public function getUseHmac(): bool
    {
        return $this->useHmac;
    }

    /**
     * Encrypts the provided text using the specified cipher algorithm and returns the encrypted data.
     *
     * @param string $text The text to encrypt.
     *
     * @return string The encrypted text, base64-encoded with version information.
     *
     * @throws EncryptException|RandomException If encryption fails.
     */
    public function encrypt(string $text): string
    {
        // Salt for PBKDF2
        $salt = random_bytes(self::SALT_LENGTH);
        // IV for encryption
        $iv = random_bytes($this->ivLength);
        $tag = '';

        $key = hash_pbkdf2(
            HashAlgorithm::SHA256->value,
            $this->getKey(),
            $salt,
            100_000,
            32,
            true
        );
        $encryptedText = openssl_encrypt(
            $text,
            strtolower($this->cipherAlgorithm),
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            '',
            self::TAG_LENGTH
        );

        if ($encryptedText === false) {
            throw new EncryptException('Encryption failed: ' . openssl_error_string());
        }

        // Format: salt|iv|tag|ciphertext
        $payload = $salt . $iv . $tag . $encryptedText;

        if ($this->getUseHmac()) {
            // Ensure the HMAC is calculated over the entire formatted string
            $hmacKey = hash_pbkdf2(
                HashAlgorithm::SHA256->value,
                $this->getKey(),
                $salt . 'hmac',
                100_000,
                self::HMAC_LENGTH,
                true
            );

            // HMAC added for integrity
            $hmac = hash_hmac(HashAlgorithm::SHA256->value, $payload, $hmacKey, true);
            $payload .= $hmac;
        }

        return CryptoVersion::V1->value . ':' . base64_encode($payload);
    }

    /**
     * Decrypts the provided encrypted text and returns the original plaintext.
     *
     * @param string $text The encrypted text to decrypt.
     *
     * @return string|null The decrypted text, or null if decryption fails or the format is invalid.
     *
     * @throws InvalidArgumentException If the provided text format is invalid.
     * @throws DecryptException
     */
    public function decrypt(string $text): ?string
    {
        [$version, $payload] = explode(':', $text, 2) + [null, null];

        if ($version !== CryptoVersion::V1->value || $payload === null) {
            throw new DecryptException('Decryption failed: ' . openssl_error_string());
        }

        $data = base64_decode($payload, true);
        if ($data === false) {
            throw new DecryptException('Invalid encoding');
        }

        $minLength = self::SALT_LENGTH + $this->ivLength + self::TAG_LENGTH;
        if ($this->useHmac) {
            $minLength += self::HMAC_LENGTH;
        }

        if (strlen($data) < $minLength) {
            throw new DecryptException('Data too short');
        }

        // Extract salt, IV, tag
        $offset = 0;
        $salt = substr($data, $offset, self::SALT_LENGTH);
        $offset += self::SALT_LENGTH;

        $iv = substr($data, $offset, $this->ivLength);
        $offset += $this->ivLength;

        $tag = substr($data, $offset, self::TAG_LENGTH);
        $offset += self::TAG_LENGTH;

        $key = hash_pbkdf2(HashAlgorithm::SHA256->value, $this->key, $salt, 100_000, 32, true);

        if ($this->useHmac) {
            $hmac = substr($data, -self::HMAC_LENGTH);
            $encryptedText = substr($data, $offset, -self::HMAC_LENGTH);

            $hmacKey = hash_pbkdf2(
                HashAlgorithm::SHA256->value,
                $this->key,
                $salt . 'hmac',
                100_000,
                self::HMAC_LENGTH,
                true
            );
            $dataToCheck = substr($data, 0, -self::HMAC_LENGTH);
            $calculatedHmac = hash_hmac(HashAlgorithm::SHA256->value, $dataToCheck, $hmacKey, true);

            if (!hash_equals($hmac, $calculatedHmac)) {
                // HMAC check failed
                throw new DecryptException('Decryption failed: ' . openssl_error_string());
            }
        } else {
            $encryptedText = substr($data, $offset);
        }

        $decryptedText = openssl_decrypt(
            $encryptedText,
            strtolower($this->cipherAlgorithm),
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        return $decryptedText !== false ? $decryptedText : null;
    }
}
