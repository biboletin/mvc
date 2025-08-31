<?php

namespace Bibo\Mvc\Core\Interfaces;

/**
 * Interface ErrorInterface
 *
 * Provides methods to manage errors in a class.
 */
interface ErrorInterface
{
    /**
     * Get the list of errors.
     *
     * @return array<string> Returns an array of error messages.
     */
    public function getErrors();

    /**
     * Add an error to the list.
     *
     * @param string $error The error message to add.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function addError(string $error): self;

    /**
     * Check if there are any errors.
     *
     * @return bool Returns true if there are errors, false otherwise.
     */
    public function hasErrors(): bool;

    /**
     * Clear all errors.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function clearErrors(): self;

    /**
     * Set the list of errors.
     *
     * @param array<string> $errors The list of errors to set.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function setError(string $error): self;

    /**
     * Set the list of errors.
     *
     * @param array<string> $errors The list of errors to set.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function setErrors(array $errors): self;

    /**
     * Get the first error message.
     *
     * @return string|null Returns the first error message or null if there are no errors.
     */
    public function getFirstError(): ?string;

    /**
     * Get the last error message.
     *
     * @return string|null Returns the last error message or null if there are no errors.
     */
    public function getLastError(): ?string;

    /**
     * Get the count of errors.
     *
     * @return int Returns the number of errors.
     */
    public function getErrorCount(): int;
}
