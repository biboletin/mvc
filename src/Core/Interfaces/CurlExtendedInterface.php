<?php

namespace Bibo\Core\Interfaces;

/**
 * CurlExtendedInterface
 * Extends the CurlInterface to provide additional functionality for handling cURL operations.
 * This interface includes methods for initializing, resetting, and managing cURL handles,
 * as well as retrieving response headers and raw responses.
 */
interface CurlExtendedInterface extends CurlInterface
{
    /**
     * Initialize the cURL handle.
     *
     * @return void
     */
    public function init(): void;

    /**
     * Reset the cURL handle to its initial state.
     *
     * @return void
     */
    public function reset(): void;

    /**
     * Set the cURL handle.
     *
     * @return mixed
     */
    public function getHandle(): mixed;

    /**
     * Set the cURL handle.
     *
     * @param mixed $handle
     *
     * @return self
     */
    public function setHandle(mixed $handle): self;

    /**
     * Get the response body from the cURL handle.
     *
     * @return string
     */
    public function getRawResponse(): string;

    /**
     * Get the response headers from the cURL handle.
     *
     * @return array
     */
    public function getResponseHeaders(): array;
}
