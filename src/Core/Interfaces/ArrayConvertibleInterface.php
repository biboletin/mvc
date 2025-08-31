<?php

namespace Bibo\Mvc\Core\Interfaces;

/**
 * Interface ArrayConvertibleInterface
 *
 * Defines a contract for classes that can be converted to an array.
 */
interface ArrayConvertibleInterface
{
    /**
     * Converts the object to an associative array.
     *
     * @return array The object properties as an associative array.
     */
    public function toArray(): array;
}
