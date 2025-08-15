<?php

namespace Bibo\Mvc\Core\Enums;

/**
 * CryptoVersion enumeration.
 * This enum defines the version of the cryptographic protocol used.
 * It is designed to be used in cryptographic operations
 * and should be chosen based on security requirements and compatibility.
 *
 * @package Biboletin\Enum
 */
enum CryptoVersion: string
{
    /*
     * Version 1 of the cryptographic protocol.
     * This is the initial version and may have basic features.
     * It is suitable for legacy systems or applications
     * that do not require advanced security features.
     */
    case V1 = 'v1';
}
