<?php

namespace Bibo\Mvc\Core\Interfaces;

use Psr\Container\ContainerInterface;

/**
 * Represents a command that can be executed within the context of a service container,
 * allowing for the retrieval of its name and description.
 */
interface CommandInterface
{
    /**
     * Retrieves the name.
     *
     * @return string The name.
     */
    public function getName(): string;

    /**
     * Retrieves the description associated with the current instance.
     *
     * @return string The description of the instance.
     */
    public function getDescription(): string;

    /**
     * Executes a specific task using the provided arguments and container.
     *
     * @param array              $args      An array of arguments to be processed during execution.
     * @param ContainerInterface $container The service container used for dependency resolution.
     *
     * @return int The result code indicating the outcome of execution.
     */
    public function execute(array $args, ContainerInterface $container): int;
}
