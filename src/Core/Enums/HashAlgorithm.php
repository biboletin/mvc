<?php

namespace Bibo\Mvc\Core\Enums;

/**
 * Enum representing different hash algorithms.
 * This enum is used to specify the hash algorithm to be used in cryptographic operations.
 * It includes SHA-256, SHA-384, and SHA-512 algorithms.
 *
 * This enum defines the available hash algorithms that can be used for cryptographic operations.
 * Each case represents a specific hash algorithm with its corresponding string value.
 * The SHA-256 algorithm produces a 256-bit hash value, SHA-384 produces a 384-bit hash value,
 * and SHA-512 produces a 512-bit hash value.
 * These algorithms are commonly used for data integrity and security purposes.
 * The enum can be used in various cryptographic operations where a specific hash algorithm is required.
 *
 * @package Biboletin\Enum
 */
enum HashAlgorithm: string
{
    /*
     * SHA-256 hash algorithm.
     * This algorithm produces a 256-bit hash value and is widely used for data integrity and security.
     * It is commonly used in various applications, including digital signatures and certificates.
     * It is considered secure and efficient for most use cases.
     */

    case SHA256 = 'sha256';

    /*
     * SHA-384 hash algorithm.
     * This algorithm produces a 384-bit hash value and is often used in security applications and protocols.
     * It provides a higher level of security than SHA-256, making it suitable for applications that require stronger
     * hashing. It is commonly used in cryptographic applications where a balance between security and performance is
     * needed.
     * * It is considered secure and provides a good compromise between speed and security.
     */

    case SHA384 = 'sha384';

    /*
     * SHA-512 hash algorithm.
     * This algorithm produces a 512-bit hash value and is used for secure hashing in various applications.
     * This is the most secure option among the three.
     * It is commonly used in cryptographic applications where a high level of security is required.
     * It is considered secure and provides the highest level of security among the three algorithms.
     * It is suitable for applications that require strong hashing and data integrity.
     */

    case SHA512 = 'sha512';

    /**
     * Returns the length of the hash output for the specified algorithm.
     * This method returns the length of the hash output in bytes for the specified hash algorithm.
     * The length is determined based on the specific hash algorithm used.
     *
     * @return int The length of the hash output in bytes.
     */
    public function length(): int
    {
        return match ($this) {
            self::SHA256 => 32,
            self::SHA384 => 48,
            self::SHA512 => 64,
        };
    }

    /**
     * Returns the length of the hash output for the specified algorithm.
     * This method returns the length of the hash output in bytes for the specified hash algorithm.
     * The length is determined based on the specific hash algorithm used.
     *
     * @param string $data
     * @param bool   $binary
     *
     * @return string The length of the hash output in bytes.
     */
    public function digest(string $data, bool $binary = true): string
    {
        return match ($this) {
            self::SHA256 => hash('sha256', $data, $binary),
            self::SHA384 => hash('sha384', $data, $binary),
            self::SHA512 => hash('sha512', $data, $binary),
        };
    }

    /**
     * Generates an HMAC (Hash-based Message Authentication Code) for the given data using the specified key.
     * This method computes an HMAC using the specified hash algorithm and key, providing a way to verify the integrity
     * and authenticity of the data.
     *
     * @param string $data The data to be hashed.
     * @param string $key  The key used for HMAC generation.
     * @param bool   $binary Whether to return the output in binary format. Defaults to true.
     *
     * @return string The generated HMAC.
     */
    public function hmac(string $data, string $key, bool $binary = true): string
    {
        return match ($this) {
            self::SHA256 => hash_hmac('sha256', $data, $key, $binary),
            self::SHA384 => hash_hmac('sha384', $data, $key, $binary),
            self::SHA512 => hash_hmac('sha512', $data, $key, $binary),
        };
    }

    /**
     * Creates a HashAlgorithm instance from a string value.
     * This method converts a string representation of a hash algorithm into the corresponding HashAlgorithm enum case.
     * If the string does not match any known hash algorithm, it returns null.
     *
     * @param string $value The string representation of the hash algorithm.
     *
     * @return self|null The corresponding HashAlgorithm enum case or null if not found.
     */
    public static function fromString(string $value): ?self
    {
        return match (strtolower($value)) {
            'sha256' => self::SHA256,
            'sha384' => self::SHA384,
            'sha512' => self::SHA512,
            default => null,
        };
    }
}
