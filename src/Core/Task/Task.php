<?php

namespace Bibo\Mvc\Core\Task;

use Bibo\Mvc\Core\Interfaces\TaskInterface;
use RuntimeException;

abstract class Task implements TaskInterface
{
    /**
     * Name of the task.
     *
     * @var string
     */
    protected string $name;

    /**
     * Description of the task.
     *
     * @var string
     */
    protected string $description;

    /**
     * Priority of the task.
     * Higher values indicate higher priority.
     *
     * @var int
     */
    protected int $priority = 0;

    /**
     * Command associated with the task.
     * This is the command that will be executed when the task runs.
     *
     * @example 'backup:database'
     *
     * @var string
     */
    protected string $command;

    /**
     * Arguments for the task command.
     * These are the parameters that will be passed to the command when it is executed.
     *
     * @example ['--all']
     *
     * @var array
     */
    protected array $arguments = [];

    /**
     * Options for the task command.
     * These are the flags or options that modify the behavior of the command.
     *
     * @example ['--force' => true]
     *
     * @var array
     */
    protected array $options = [];

    /**
     * Environment variables for the task.
     * These are the environment variables that will be set when the task runs.
     *
     *  @example ['DB_HOST' => 'localhost', 'DB_USER' => 'root', 'DB_PASSWORD' => 'password']
     *
     * @var array
     */
    protected array $env = [];

    /**
     * Task constructor.
     */
    public function __construct()
    {
        // Initialization code if needed
    }

    /**
     * Get the name of the task.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the description of the task.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get the priority of the task.
     *
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * Get the command associated with the task.
     *
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * Get the arguments for the task command.
     *
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Get the options for the task command.
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Get the environment variables for the task.
     *
     * @return array
     */
    public function getEnv(): array
    {
        return $this->env;
    }

    /**
     * Set the name of the task.
     *
     * @param string $name
     *
     * @return TaskInterface
     */
    public function setName(string $name): TaskInterface
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set the description of the task.
     *
     * @param string $description
     *
     * @return TaskInterface
     */
    public function setDescription(string $description): TaskInterface
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Set the command for the task.
     *
     * @param string $command
     *
     * @return void
     */
    public function setCommand(string $command): void
    {
        $this->command = $command;
    }

    /**
     * Set the priority of the task.
     *
     * @param int $priority
     *
     * @return TaskInterface
     */
    public function setPriority(int $priority): TaskInterface
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * Set the arguments for the task command.
     *
     * @param array $arguments
     *
     * @return void
     */
    public function setArguments(array $arguments): void
    {
        $this->arguments = $arguments;
    }

    /**
     * Set the options for the task command.
     *
     * @param array $options
     *
     * @return void
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    /**
     * Set the environment variables for the task.
     *
     * @param array $env
     *
     * @return void
     */
    public function setEnv(array $env): void
    {
        $this->env = $env;
    }

    /**
     * Run the task.
     * This method should be implemented in subclasses to define the task's behavior.
     *
     * @return void
     */
    public function run(): void
    {
        // Implement the logic to run the task
        // This method should be overridden in subclasses
        throw new RuntimeException('The run method must be implemented in the subclass.');
    }
}
