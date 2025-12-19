<?php

if (!function_exists('formatBytes')) {
    /**
     * Format bytes into a human-readable string.
     *
     * @param int $bytes
     * @param int $decimals
     *
     * @return string
     */
    function formatBytes(int $bytes, int $decimals = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = floor((strlen((string) $bytes) - 1) / 3);

        return sprintf('%.' . $decimals . 'f %s', $bytes / pow(1024, $factor), $units[$factor]);
    }
}

if (!function_exists('formatTime')) {
    /**
     * Format time in seconds into a human-readable string.
     *
     * @param int $seconds
     *
     * @return string
     */
    function formatTime(int $seconds): string
    {
        if ($seconds < 60) {
            return '{' . $seconds . '} seconds';
        } elseif ($seconds < 3600) {
            return floor($seconds / 60) . ' minutes';
        } elseif ($seconds < 86400) {
            return floor($seconds / 3600) . ' hours';
        } else {
            return floor($seconds / 86400) . ' days';
        }
    }
}

if (!function_exists('formatDate')) {
    /**
     * Format a date string into a human-readable format.
     *
     * @param string $date
     * @param string $format
     *
     * @return string
     */
    function formatDate(string $date, string $format = 'Y-m-d H:i:s'): string
    {
        $timestamp = strtotime($date);

        return date($format, $timestamp);
    }
}

if (!function_exists('formatNumber')) {
    /**
     * Format a number with grouped thousands.
     *
     * @param float $number
     * @param int $decimals
     *
     * @return string
     */
    function formatNumber(float $number, int $decimals = 2): string
    {
        return number_format($number, $decimals, '.', ',');
    }
}

if (!function_exists('formatPercentage')) {
    /**
     * Format a number as a percentage.
     *
     * @param float $number
     * @param int $decimals
     *
     * @return string
     */
    function formatPercentage(float $number, int $decimals = 2): string
    {
        return number_format($number * 100, $decimals) . '%';
    }
}

if (!function_exists('formatCurrency')) {
    /**
     * Format a number as currency.
     *
     * @param float $amount
     * @param string $currency
     * @param int $decimals
     *
     * @return string
     */
    function formatCurrency(float $amount, string $currency = 'USD', int $decimals = 2): string
    {
        return sprintf('%s%.2f', config('app.currency_symbol', '$'), $amount);
    }
}

if (!function_exists('formatFileSize')) {
    /**
     * Format a file size into a human-readable string.
     *
     * @param int $size
     * @param int $decimals
     *
     * @return string
     */
    function formatFileSize(int $size, int $decimals = 2): string
    {
        return formatBytes($size, $decimals);
    }
}

if (!function_exists('formatDuration')) {
    /**
     * Format a duration in seconds into a human-readable string.
     *
     * @param int $seconds
     *
     * @return string
     */
    function formatDuration(int $seconds): string
    {
        return formatTime($seconds);
    }
}

if (!function_exists('formatTimestamp')) {
    /**
     * Format a timestamp into a human-readable date string.
     *
     * @param int $timestamp
     * @param string $format
     *
     * @return string
     */
    function formatTimestamp(int $timestamp, string $format = 'Y-m-d H:i:s'): string
    {
        return date($format, $timestamp);
    }
}

if (!function_exists('formatJson')) {
    /**
     * Format a JSON string with pretty print.
     *
     * @param string $json
     *
     * @return string
     */
    function formatJson(string $json): string
    {
        $decoded = json_decode($json, true);

        return json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}

if (!function_exists('formatXml')) {
    /**
     * Format an XML string with indentation.
     *
     * @param string $xml
     *
     * @return string
     */
    function formatXml(string $xml): string
    {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml);

        return $dom->saveXML();
    }
}

if (!function_exists('formatHtml')) {
    /**
     * Format an HTML string with indentation.
     *
     * @param string $html
     *
     * @return string
     */
    function formatHtml(string $html): string
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        return $dom->saveHTML();
    }
}

if (!function_exists('formatUrl')) {
    /**
     * Format a URL by adding a scheme if missing.
     *
     * @param string $url
     *
     * @return string
     */
    function formatUrl(string $url): string
    {
        if (!preg_match('/^https?:\/\//', $url)) {
            $url = 'https://' . $url;
        }

        return rtrim($url, '/');
    }
}

if (!function_exists('formatEmail')) {
    /**
     * Format an email address.
     *
     * @param string $email
     *
     * @return string
     */
    function formatEmail(string $email): string
    {
        return strtolower(trim($email));
    }
}

if (!function_exists('formatPhoneNumber')) {
    /**
     * Format a phone number to a standard format.
     *
     * @param string $number
     *
     * @return string
     */
    function formatPhoneNumber(string $number): string
    {
        // Remove non-numeric characters
        $number = preg_replace('/\D/', '', $number);
        // Format as (XXX) XXX-XXXX
        return preg_replace('/(\d{3})(\d{3})(\d{4})/', '($1) $2-$3', $number);
    }
}

if (!function_exists('formatAddress')) {
    /**
     * Format an address into a human-readable string.
     *
     * @param array $address
     *
     * @return string
     */
    function formatAddress(array $address): string
    {
        return implode(', ', array_filter([
            $address['street'] ?? '',
            $address['city'] ?? '',
            $address['state'] ?? '',
            $address['zip'] ?? '',
            $address['country'] ?? ''
        ]));
    }
}

if (!function_exists('formatList')) {
    /**
     * Format a list of items into a comma-separated string.
     *
     * @param array $items
     *
     * @return string
     */
    function formatList(array $items): string
    {
        return implode(', ', $items);
    }
}

if (!function_exists('formatCsv')) {
    /**
     * Format an array into a CSV string.
     *
     * @param array $data
     * @param string $delimiter
     *
     * @return string
     */
    function formatCsv(array $data, string $delimiter = ','): string
    {
        $output = fopen('php://temp', 'r+');
        fputcsv($output, $data, $delimiter);
        rewind($output);

        return stream_get_contents($output);
    }
}
