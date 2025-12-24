<?php

namespace Bibo\Mvc\Core\Utilities\Utils;

trait MathUtils
{
    public static function random(int $min, int $max): int
    {
    }

    public static function roundUp(float $number, int $precision = 0): float
    {
    }

    public static function roundDown(float $number, int $precision = 0): float
    {
    }

    public static function roundHalfUp(float $number, int $precision = 0): float
    {
    }

    public static function roundHalfDown(float $number, int $precision = 0): float
    {
    }

    public static function roundHalfEven(float $number, int $precision = 0): float
    {
    }

    public static function roundAwayFromZero(float $number, int $precision = 0): float
    {
    }

    public static function roundToZero(float $number, int $precision = 0): float
    {
    }

    public static function factorial(int $number): int
    {
    }

    public static function fibonacci(int $number): int
    {
    }

    public static function gcd(int $a, int $b): int
    {
    }

    public static function lcm(int $a, int $b): int
    {
    }

    public static function isPrime(int $number): bool
    {
    }

    public static function isEven(int $number): bool
    {
    }

    public static function isOdd(int $number): bool
    {
    }

    public static function isPrimeFast(int $number): bool
    {
    }

    public static function isEvenFast(int $number): bool
    {
    }

    public static function isOddFast(int $number): bool
    {
    }

    public static function power(int $number): float
    {
    }

    public static function squareRoot(int $number): float
    {
    }

    public static function cubeRoot(int $number): float
    {
    }

    public static function logarithm(int $number): float
    {
    }

    public static function logarithmBase(int $number, int $base): float
    {
    }

    public static function average(int $number): float
    {
    }

    public static function median(int $number): float
    {
    }

    public static function mode(int $number): float
    {
    }

    public static function percentile(int $number): float
    {
    }

    public static function max(array $numbers): float
    {
        return max($numbers);
    }

    public static function min(array $numbers): float
    {
        return min($numbers);
    }

    public static function sum(array $numbers): float
    {
        return array_sum($numbers);
    }
}
