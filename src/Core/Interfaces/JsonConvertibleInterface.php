<?php

namespace Bibo\Mvc\Core\Interfaces;

/**
 * Interface JsonConvertibleInterface
 *
 * Provides a method to convert an object to a JSON string.
 */
interface JsonConvertibleInterface
{
    /**
     * Converts the object to a JSON string.
     *
     * @param int $flags Optional flags for JSON encoding.
     *
     * @return string The object properties as a JSON string.
     */
    public function toJson(int $flags): string;
}
