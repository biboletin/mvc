<?php

namespace Bibo\Core\Wrapper\Curl;

use Bibo\Core\Interfaces\CurlInterface;
use RuntimeException;

class CurlMultiWrapper extends AbstractCurlWrapper
{
    protected mixed $multiHandle;
    protected array $handlers = [];

    public function __construct()
    {
        $this->multiHandle = curl_multi_init();
        if ($this->multiHandle === false) {
            throw new RuntimeException('Unable to initialize cURL multi handle');
        }
    }

    public function addHandle(CurlInterface $handle): void
    {
        $this->handlers[] = $handle;
        $ch = $handle->getHandle();

        if (curl_multi_add_handle($this->multiHandle, $ch) !== CURLM_OK) {
            throw new RuntimeException('Unable to add cURL handle to multi handle');
        }
    }

    public function exec(): void
    {
        $running = 0;

        do {
            $status = curl_multi_exec($this->multiHandle, $running);

            if ($status !== CURLM_OK && $status !== CURLM_CALL_MULTI_PERFORM) {
                throw new RuntimeException('cURL multi error: ' . curl_multi_strerror($status));
            }

            if (curl_multi_select($this->multiHandle) === -1) {
                usleep(100); // Sleep briefly to prevent busy loop
            }
        } while ($running > 0);

        // Ensure all messages are processed
        while ($info = curl_multi_info_read($this->multiHandle)) {
            if ($info['result'] !== CURLE_OK) {
                throw new RuntimeException('cURL error on handle: ' . curl_strerror($info['result']));
            }
        }

        $this->close(); // Automatically close multi handle and easy handles
    }

    public function getAllResponses(): array
    {
        $responses = [];

        foreach ($this->handlers as $index => $handle) {
            $ch = $handle->getHandle();
            $body = curl_multi_getcontent($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $headers = $handle->getResponseHeaders();

            $responses[] = [
                'body' => $body,
                'status' => $status,
                'headers' => $headers,
                'handle' => $handle,
            ];
        }

        return $responses;
    }

    public function getResponseBodies(): array
    {
        $bodies = [];
        foreach ($this->handlers as $handle) {
            $bodies[] = curl_multi_getcontent($handle->getHandle());
        }
        return $bodies;
    }

    public function getStatusCodes(): array
    {
        $codes = [];
        foreach ($this->handlers as $handle) {
            $codes[] = curl_getinfo($handle->getHandle(), CURLINFO_HTTP_CODE);
        }
        return $codes;
    }

    public function getAllHeaders(): array
    {
        $headers = [];
        foreach ($this->handlers as $handle) {
            $headers[] = $handle->getResponseHeaders();
        }
        return $headers;
    }

    public function close(): void
    {
        foreach ($this->handlers as $handle) {
            curl_multi_remove_handle($this->multiHandle, $handle->getHandle());
            $handle->close();
        }

        curl_multi_close($this->multiHandle);
    }

    public function init()
    {

    }
}
