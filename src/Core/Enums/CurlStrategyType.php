<?php

namespace Bibo\Core\Enum;

use InvalidArgumentException;

/**
 * CurlStrategyType enum class
 */
enum CurlStrategyType: string
{
    /**
     * Single cURL handle strategy.
     */
    case SINGLE = 'single';

    /**
     * Multi cURL handle strategy.
     */
    case MULTI = 'multi';

    /**
     * Creates an instance of the enum from a string value.
     *
     * @param string $value
     *
     * @return self
     */
    public static function fromString(string $value): self
    {
        return match (strtolower($value)) {
            'single' => self::SINGLE,
            'multi' => self::MULTI,
            default => throw new InvalidArgumentException("Invalid strategy type: $value"),
        };
    }

}
