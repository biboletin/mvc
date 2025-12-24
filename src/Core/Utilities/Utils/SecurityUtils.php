<?php

namespace Bibo\Mvc\Core\Utilities\Utils;

use Random\RandomException;

trait SecurityUtils
{
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Generate a CSRF token
     *
     * @return string
     *
     * @throws RandomException
     */
    public static function generateCsrfToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    public static function generateRandomString(int $length = 10): string
    {
    }

    public static function generateRandomNumber(int $length = 6): int
    {
    }

    public static function generateRandomToken(): string
    {
    }

    public static function generateRandomSalt(): string
    {
    }

    public static function generateRandomHash(): string
    {
    }

    public static function generateRandomIv(): int
    {
    }

    public static function generateRandomMac(): string
    {
    }

    public static function generateRandomUuid(): string
    {
    }

    public static function sanitizeInput(string $string): string
    {
        return htmlspecialchars(strip_tags($string), ENT_QUOTES, 'UTF-8');
    }

    public static function sanitizeOutput(string $string): string
    {
        return htmlspecialchars(strip_tags($string), ENT_QUOTES, 'UTF-8');
    }
}
