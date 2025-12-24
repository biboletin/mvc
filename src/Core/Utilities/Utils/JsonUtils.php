<?php

namespace Bibo\Mvc\Core\Utilities\Utils;

/**
 * Class JsonUtils
 *
 * @package Bibo\Mvc\Core\Utilities\Utils
 */
trait JsonUtils
{
    /**
     * Encode data to json
     *
     * @param array $data
     *
     * @return string
     */
    public static function toJson(array $data = []): string
    {
        if (empty(array_filter($data))) {
            return '';
        }

        return json_encode($data);
    }

    /**
     * Decode json to array
     *
     * @param string $json
     *
     * @return mixed
     */
    public static function fromJson(string $json): array
    {
        if (trim($json) === '') {
            return [];
        }

        return json_decode($json, true);
    }

    /**
     * Pretty print JSON
     *
     * @param string $json
     *
     * @return string
     */
    public static function prettyPrint(string $json): string
    {
        if (trim($json) === '') {
            return '';
        }

        return json_encode(json_decode($json), JSON_PRETTY_PRINT);
    }

    /**
     * Check if JSON is valid
     *
     * @param string $json
     * @param int $depth
     * @param int $flags
     *
     * @return bool
     */
    public static function isValidJson(string $json, int $depth = 512, int $flags = 0): bool
    {

        return json_validate($json, $depth, $flags);
    }

    /**
     * Check if a string is a valid JSON string
     *
     * @param string $string
     *
     * @return bool
     */
    public static function isJsonString(string $string): bool
    {
        if (trim($string) === '') {
            return false;
        }

        return ctype_digit(str_replace(['[',']','"'], '', $string));
    }

    /**
     * Check if data is a valid JSON string
     *
     * @param mixed $data
     *
     * @return bool
     */
    public static function isJson(mixed $data): bool
    {
        return is_string($data) && self::isValidJson($data) && self::isJsonString($data);
    }
}
