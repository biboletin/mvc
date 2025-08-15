<?php

namespace Bibo\Mvc\Core\Enums;

/**
 * ApiVersion enumeration.
 * This enum defines the API version used in the application.
 * It is designed to be used for versioning APIs to ensure compatibility
 * and to allow for future enhancements without breaking existing functionality.
 *
 * @package Biboletin\Enum
 */
enum ApiVersion: string
{
    /**
     * Version 1 of the API.
     * This is the initial version and may include basic features and functionalities.
     */
    const V1 = 'v1';
}
