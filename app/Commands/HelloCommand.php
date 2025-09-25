<?php

namespace Bibo\App\Commands;

use Bibo\Mvc\Core\Interfaces\CommandInterface;
use Psr\Container\ContainerInterface;

class HelloCommand implements CommandInterface
{
    /**
     * Retrieves the name.
     *
     * @return string The name.
     */
    public function getName(): string
    {
        return 'hello';
    }

    /**
     * Retrieves the description associated with the current instance.
     *
     * @return string The description of the instance.
     */
    public function getDescription(): string
    {
        return 'Prints "Hello" to the screen';
    }

    /**
     * Executes a specific task using the provided arguments and container.
     *
     * @param array              $args      An array of arguments to be processed during execution.
     * @param ContainerInterface $container The service container used for dependency resolution.
     *
     * @return int The result code indicating the outcome of execution.
     */
    public function execute(array $args, ContainerInterface $container): int
    {
        echo 'Hello' . PHP_EOL;

        return 1;
    }
}
