<?php

namespace Bibo\Mvc\Core\Interfaces;

interface TaskInterface
{
    /**
     * Get the name of the task.
     *
     * @return string The name of the task.
     */
    public function getName(): string;

    /**
     * Set the name of the task.
     *
     * @param string $name The name to set for the task.
     *
     * @return TaskInterface The current task instance for chaining.
     */
    public function setName(string $name): TaskInterface;

    /**
     * Get the description of the task.
     *
     * @return string The description of the task.
     */
    public function getDescription(): string;

    /**
     * Set the description of the task.
     *
     * @param string $description The description to set for the task.
     *
     * @return TaskInterface The current task instance for chaining.
     */
    public function setDescription(string $description): TaskInterface;

    /**
     * Get the priority of the task.
     *
     * @return int The priority of the task.
     */
    public function getPriority(): int;

    /**
     * Set the priority of the task.
     *
     * @param int $priority The priority to set for the task.
     *
     * @return TaskInterface The current task instance for chaining.
     */
    public function setPriority(int $priority): TaskInterface;

    /**
     * Check if the task is enabled.
     *
     * @return bool True if the task is enabled, false otherwise.
     */
    public function isEnabled(): bool;

    /**
     * Enable or disable the task.
     *
     * @param bool $enabled True to enable the task, false to disable it.
     *
     * @return TaskInterface The current task instance for chaining.
     */
    public function setEnabled(bool $enabled): TaskInterface;

    /**
     * Get the next task in the chain.
     *
     * @return TaskInterface|null The next task, or null if there is no next task.
     */
    public function getNext(): ?TaskInterface;

    /**
     * Set the next task in the chain.
     *
     * @param TaskInterface $task The next task to set.
     *
     * @return TaskInterface The current task instance for chaining.
     */
    public function setNext(TaskInterface $task): TaskInterface;

    /**
     * Check if there is a next task in the chain.
     *
     * @return bool True if there is a next task, false otherwise.
     */
    public function hasNext(): bool;

    /**
     * Clear the next task in the chain.
     *
     * This method should remove the reference to the next task.
     *
     * @return TaskInterface The current task instance for chaining.
     */
    public function clearNext(): TaskInterface;

    /**
     * Convert the task to an associative array.
     *
     * This method should return the properties of the task as an associative array.
     *
     * @return array The task properties as an associative array.
     */
    public function toArray(): array;

    /**
     * Execute the task logic.
     *
     * This method should contain the logic for the task to execute.
     */
    public function run(): void;
}
