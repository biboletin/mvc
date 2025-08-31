<?php

namespace Bibo\Mvc\Core\Task;

use Bibo\Mvc\Core\Interfaces\TaskInterface;
use InvalidArgumentException;

class BackupDatabase extends Task
{
    protected string $name = 'Backup Database';

    protected string $description = 'Task to backup the database';

    protected int $priority = 10; // Default priority can be adjusted

    protected string $command = 'backup:database';

    protected array $arguments = [];

    protected array $options = [];

    protected array $env = [];

    protected bool $enabled = true;

    protected ?TaskInterface $next = null;

    /**
     * BackupDatabase constructor.
     */
    public function __construct()
    {
        parent::__construct();
        // Additional initialization if needed
    }

    /**
     * Run the backup database task.
     * This method should contain the logic to perform the database backup.
     * For demonstration purposes, it simply prints a message.
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function run(): void
    {
        echo 'Running database backup task...';
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): TaskInterface
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getNext(): ?TaskInterface
    {
        return $this->next;
    }

    public function setNext(?TaskInterface $task): TaskInterface
    {
        $this->next = $task;
        return $this;
    }

    public function hasNext(): bool
    {
        return $this->next !== null;
    }

    public function clearNext(): TaskInterface
    {
        $this->next = null;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'priority' => $this->priority,
            'command' => $this->command,
            'arguments' => $this->arguments,
            'options' => $this->options,
            'env' => $this->env,
            'enabled' => $this->enabled,
        ];
    }
}
