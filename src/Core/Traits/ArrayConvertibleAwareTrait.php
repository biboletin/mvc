<?php

namespace Bibo\Mvc\Core\Traits;

/**
 * Trait ArrayConvertibleAwareTrait
 *
 * Provides a method to convert an object to an associative array.
 */
trait ArrayConvertibleAwareTrait
{
    /**
     * Converts the object to an associative array.
     *
     * @return array The object properties as an associative array.
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
