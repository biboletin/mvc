<?php

namespace Bibo\Mvc\Core\Enums;

/**
 * AppVersion enumeration.
 * This enum defines the version of the application.
 * It is designed to be used for versioning the application to ensure compatibility
 * and to allow for future enhancements without breaking existing functionality.
 *
 * @package Biboletin\Enum
 */
enum AppVersion: string
{
    /*
     * Version 1.0.0 of the application.
     * This is the initial version and may include basic features and functionalities.
     */
    case V1_0_0 = '1.0.0';

    /**
     * Returns the version value with a specified prefix.
     * This method allows you to prepend a prefix to the version string,
     * which can be useful for formatting or categorization purposes.
     *
     * @param string $prefix
     *
     * @return string
     */
    public function withPrefix(string $prefix): string
    {
        return $prefix . $this->value;
    }
}
