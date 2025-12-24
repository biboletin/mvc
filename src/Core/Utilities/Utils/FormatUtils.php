<?php

namespace Bibo\Mvc\Core\Utilities\Utils;

trait FormatUtils
{
    public function formatDate($date): string
    {
    }

    public function formatDateTime($dateTime)
    {
    }

    public function formatTime($time)
    {
    }

    public function formatNumber($number)
    {
    }

    public function formatCurrency($currency)
    {
    }

    public function formatBytes($bytes)
    {
    }

    public function formatFileSize($size)
    {
    }

    public function formatPercent($percent)
    {
    }

    public function formatPhone($phone)
    {
    }

    public function formatEmail($email)
    {
    }

    public function formatUrl($url)
    {
    }

    public function formatIp($ip)
    {
    }

    public static function toCamelCase(string $string): string
    {
        if (trim($string) === '') {
            return '';
        }

        $result = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string)));

        return lcfirst($result);
    }

    public static function toSnakeCase(string $string): string
    {
        if (trim($string) === '') {
            return '';
        }

        return strtolower(preg_replace('/[A-Z]/', '_$0', $string));
    }

    public static function toTitleCase(string $string): string
    {
        if (trim($string) === '') {
            return '';
        }

        return ucwords(strtolower($string));
    }
}
