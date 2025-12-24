<?php

namespace Bibo\Mvc\Core\Enums;

/**
 * CipherAlgorithm enumeration.
 * This enum defines various cipher algorithms used for encryption.
 * It includes modern and secure algorithms like AES in CBC and GCM modes,
 * CHACHA20-POLY1305, and SM4 in GCM/CCM/CTR modes.
 * It is designed to be used in cryptographic operations
 * and should be chosen based on security requirements and performance considerations.
 * * @package Biboletin\Enum
 */
enum CipherAlgorithm: string
{
    // ✅ AES in CBC and GCM (modern & secure if used correctly)

    /*
     * AES-128-CBC: AES with a 128-bit key in CBC mode.
     * Recommended for general use with proper IV management.
     */

    case AES_128_CBC = 'aes-128-cbc';

    /*
     * AES-192-CBC: AES with a 192-bit key in CBC mode.
     * Provides a higher security level than AES-128.
     */

    case AES_192_CBC = 'aes-192-cbc';

    /*
     * AES-256-CBC: AES with a 256-bit key in CBC mode.
     * Offers the highest security level among AES CBC modes.
     */

    case AES_256_CBC = 'aes-256-cbc';

    /*
     * AES-128-GCM: AES with a 128-bit key in GCM mode.
     * Provides authenticated encryption and is suitable for modern applications.
     */

    case AES_128_GCM = 'aes-128-gcm';

    /*
     * AES-192-GCM: AES with a 192-bit key in GCM mode.
     * Offers a balance between security and performance.
     */

    case AES_192_GCM = 'aes-192-gcm';

    /*
     * AES-256-GCM: AES with a 256-bit key in GCM mode.
     * Provides the highest security level and is recommended for sensitive data.
     */

    case AES_256_GCM = 'aes-256-gcm';

    // ✅ CHACHA20-POLY1305 (secure & recommended for mobile / CPU without AES acceleration)

    /*
     * CHACHA20-POLY1305: A combined cipher that uses the ChaCha20 stream cipher
     * and the Poly1305 message authentication code.
     * Recommended for environments where AES is not available
     * or for mobile devices.
     */

    case CHACHA20_POLY1305 = 'chacha20-poly1305';

    // ✅ SM4 in GCM/CCM/CTR mode only (omit ECB, pad, or HMAC combos)

    /*
     * SM4-GCM: SM4 cipher in Galois/Counter Mode (GCM).
     * Provides authenticated encryption and is suitable for modern applications.
     * GCM mode is designed to provide both confidentiality and integrity
     * for the data being encrypted.
     * It is important to manage the nonce/IV properly
     * to avoid security issues.
     * This mode is generally used for performance-sensitive applications
     * where authenticated encryption is required.
     * It is important to ensure that the integrity of the data
     * is verified through the GCM authentication tag.
     * It is important to manage the nonce/IV properly
     * to avoid security issues,
     * as nonce reuse can lead to serious vulnerabilities.
     * This mode is generally used for performance-sensitive applications
     * where authenticated encryption is required,
     * but the data size is large.
     */

    case SM4_GCM = 'sm4-gcm';

    /*
     * SM4-CCM: SM4 cipher in Counter with CBC-MAC (CCM) mode.
     * Provides authenticated encryption and is suitable for modern applications.
     * CCM mode is similar to GCM but uses a different authentication mechanism.
     * It is designed for use in environments
     * where GCM is not available or suitable.
     * CCM mode is generally used for smaller data sizes
     * and is often used in IoT applications.
     * It is important to manage the nonce/IV properly
     * to avoid security issues.
     * This mode is less common for SM4
     * and is generally not recommended
     * for new applications due to potential security issues
     * with nonce reuse and lack of authentication.
     * It is important to ensure that the integrity of the data
     * is verified through other means,
     * such as HMAC or a similar mechanism.
     * This mode is generally used for performance-sensitive applications
     * where authenticated encryption is required,
     * but the data size is small.
     * It is important to manage the nonce/IV properly
     * to avoid security issues.
     */

    case SM4_CCM = 'sm4-ccm';

    /*
     * SM4-CTR: SM4 cipher in Counter (CTR) mode.
     * Provides a stream cipher-like operation and is suitable for certain applications.
     * Note: CTR mode does not provide authentication,
     * so it should be used with caution.
     * Ensure that the integrity of the data is verified
     * through other means, such as HMAC or a similar mechanism.
     * This mode is generally used for performance-sensitive applications
     * where authenticated encryption is not required.
     * It is important to manage the nonce/IV properly
     * to avoid security issues.
     * This mode is less common for SM4 and is generally not recommended
     * for new applications due to potential security issues
     * with nonce reuse and lack of authentication.
     */

    case SM4_CTR = 'sm4-ctr';

    // ⚠️ Optional: CBC for SM4 (only if you trust padding/hmac handling)

    /*
     * SM4-CBC: SM4 cipher in Cipher Block Chaining (CBC) mode.
     * Use with caution, ensuring proper IV management and padding.
     * This mode is less common for SM4 and is generally not recommended
     * for new applications due to potential security issues
     * with padding and IV reuse.
     */

    case SM4_CBC = 'sm4-cbc';
}
