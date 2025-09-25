<?php

namespace Bibo\Mvc\Core\Console;

use Bibo\Mvc\Core\Interfaces\CommandInterface;
use Psr\Container\ContainerInterface;

class ConsoleKernel
{
    private array $commands = [];

    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function register(CommandInterface $command): void
    {
        $this->commands[$command->getName()] = $command;
    }

    public function run(array $argv): int
    {
        $commandName = $argv[1] ?? 'help';
        $args = array_slice($argv, 2);

        if (!$commandName || !isset($this->commands[$commandName])) {
            $this->printHelp();
            return 1;
        }

        return $this->commands[$commandName]->execute($args, $this->container);
    }

    public function printHelp(): void
    {
        echo "Usage: php console <command> [options]\n\n";
        echo 'Available commands:' . PHP_EOL;

        foreach ($this->commands as $name => $command) {
            printf("  %-20s %s\n", $name, $command->getDescription());
        }
    }
}
