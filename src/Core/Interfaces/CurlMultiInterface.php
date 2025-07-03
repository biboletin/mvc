<?php

namespace Bibo\Core\Interfaces;

/**
 * CurlMultiInterface
 *
 * Extends the CurlInterface to provide functionality for handling multiple cURL operations simultaneously.
 * This interface includes methods for adding, removing, and executing multiple cURL handles,
 * as well as managing the multi-handle state and monitoring running handles.
 *
 * Multi-handle cURL operations allow for parallel execution of multiple HTTP requests,
 * which can significantly improve performance when making multiple requests.
 *
 * @see \Bibo\Core\Interfaces\CurlInterface For core cURL functionality
 * @see \Bibo\Core\Wrapper\Curl\CurlMultiWrapper For implementation details
 */
interface CurlMultiInterface extends CurlInterface
{
    /**
     * Add a cURL handle to the multi-handle.
     *
     * This method adds a single cURL handle to the multi-handle for parallel execution.
     * Multiple handles can be added to the same multi-handle.
     *
     * @param CurlInterface $handle The cURL handle to add to the multi-handle
     *
     * @return void
     *
     * @see curl_multi_add_handle() PHP's native function for adding handles to a multi-handle
     */
    public function addHandle(CurlInterface $handle): void;

    /**
     * Get the cURL multi-handle.
     *
     * This method retrieves the underlying cURL multi-handle resource.
     * It can be used for advanced operations or debugging.
     *
     * @return mixed The cURL multi-handle resource
     *
     * @see curl_multi_init() PHP's native function for initializing a multi-handle
     */
    public function getHandle(): mixed;

    /**
     * Remove a cURL handle from the multi-handle.
     *
     * This method removes a previously added cURL handle from the multi-handle.
     * It should be called when a handle is no longer needed or after it has completed execution.
     *
     * @param CurlInterface $handle The cURL handle to remove from the multi-handle
     *
     * @return void
     *
     * @see curl_multi_remove_handle() PHP's native function for removing handles from a multi-handle
     */
    public function removeHandle(CurlInterface $handle): void;

    /**
     * Executes all cURL handles in the multi-handle.
     *
     * This method processes all the cURL handles added to the multi-handle in parallel.
     * It blocks until all handles have completed their requests.
     *
     * Note: While this method must return a string to maintain interface compatibility,
     * multi-handle implementations typically don't return a response directly.
     * Instead, you should retrieve responses from individual handles after execution.
     *
     * @return string Empty string for compatibility with CurlInterface
     *
     * @see curl_multi_exec() PHP's native function for executing multi-handle requests
     */
    public function exec(): string;

    /**
     * Wait for activity on any curl-connection.
     *
     * This method blocks until there is activity on any of the connections or until
     * the timeout is reached. It's useful for implementing non-blocking cURL operations.
     *
     * @return int Number of descriptors selected or -1 on error
     *
     * @see curl_multi_select() PHP's native function for waiting on multi-handle activity
     */
    public function select(): int;

    /**
     * Close the multi-handle and release resources.
     *
     * This method closes the multi-handle and frees all resources associated with it.
     * It should be called when the multi-handle is no longer needed to prevent memory leaks.
     * Any handles added to the multi-handle should be removed before closing.
     *
     * @return void
     *
     * @see curl_multi_close() PHP's native function for closing multi-handles
     */
    public function close(): void;

    /**
     * Get the number of running handles.
     *
     * This method returns the number of cURL handles that are still executing requests.
     * It's useful for monitoring the progress of parallel requests.
     *
     * @return int The number of running handles
     *
     * @see curl_multi_exec() PHP's native function that updates the running handles count
     */
    public function getRunningHandlesCount(): int;
}
