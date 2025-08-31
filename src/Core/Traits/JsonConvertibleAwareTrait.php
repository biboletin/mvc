<?php

namespace Bibo\Mvc\Core\Traits;

/**
 * Trait JsonConvertibleAwareTrait
 *
 * Provides a method to convert an object to a JSON string.
 */
trait JsonConvertibleAwareTrait
{
    /**
     * Converts the object to an associative array.
     *
     * @param int $flags
     *
     * @return string The object properties as an associative array.
     */
    public function toJson(int $flags = 0): string
    {
        return json_encode($this->toArray(), $flags);
    }
}
