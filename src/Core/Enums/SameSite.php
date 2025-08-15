<?php

namespace Bibo\Mvc\Core\Enums;

use InvalidArgumentException;

enum SameSite: string
{
    case Lax = 'Lax';
    case Strict = 'Strict';
    case None = 'None';

    public static function fromValue(string $value): self
    {
        return match (strtolower($value)) {
            'lax' => self::Lax,
            'strict' => self::Strict,
            'none' => self::None,
            default => throw new InvalidArgumentException('Invalid SameSite value: ' . $value),
        };
    }
}
