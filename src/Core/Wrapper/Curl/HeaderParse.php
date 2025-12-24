<?php

namespace Bibo\Mvc\Core\Wrapper\Curl;

class HeaderParse
{
    /**
     * Parses raw HTTP response headers into an associative array.
     *
     * @param string $rawHeaders Raw header string (e.g., from cURL's header output).
     *
     * @return array Parsed headers in ['Header-Name' => ['value1', 'value2']] format.
     */
    public static function parse(string $rawHeaders): array
    {
        $lines = preg_split("/\r\n|\n|\r/", trim($rawHeaders));
        $headers = [];

        foreach ($lines as $line) {
            if (str_starts_with(strtolower($line), 'http/')) {
                // Handle HTTP status line (can be used for protocol/version if needed)
                continue;
            }

            if (str_contains($line, ':')) {
                [$name, $value] = explode(':', $line, 2);
                $name = trim($name);
                $value = trim($value);

                // Normalize: handle multiple values for the same header (e.g., Set-Cookie)
                $lower = strtolower($name);
                if (!isset($headers[$lower])) {
                    $headers[$lower] = [
                        'name'   => $name,
                        'values' => [$value],
                    ];
                } else {
                    $headers[$lower]['values'][] = $value;
                }
            }
        }

        // Rebuild an array in the original case format expected by Headers class
        $final = [];
        foreach ($headers as $data) {
            $final[$data['name']] = $data['values'];
        }

        return $final;
    }
}
