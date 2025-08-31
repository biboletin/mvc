<?php

namespace Bibo\Mvc\Core\Traits;

/**
 * Trait ErrorAwareTrait
 *
 * Provides methods to manage errors in a class.
 */
trait ErrorAwareTrait
{
    /**
     * List of errors
     *
     * @var array<string>
     */
    protected array $errors = [];

    /**
     * Get the list of errors.
     *
     * @return array<string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Add an error to the list.
     *
     * @param string $error The error message to add.
     */
    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * Check if there are any errors.
     *
     * @return bool True if there are errors, false otherwise.
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Clear all errors.
     */
    public function clearErrors(): void
    {
        $this->errors = [];
    }

    /**
     * Set the list of errors.
     *
     * @param array<string> $errors The list of errors to set.
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * Get the first error from the list.
     *
     * @return string|null The first error message or null if there are no errors.
     */
    public function getFirstError(): ?string
    {
        return $this->errors[0] ?? null;
    }

    /**
     * Get the last error from the list.
     *
     * @return string|null The last error message or null if there are no errors.
     */
    public function getLastError(): ?string
    {
        return end($this->errors) ?: null;
    }

    /**
     * Get the count of errors.
     *
     * @return int The number of errors.
     */
    public function getErrorCount(): int
    {
        return count($this->errors);
    }
}
