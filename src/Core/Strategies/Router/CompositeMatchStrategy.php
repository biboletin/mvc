<?php

/**
 * Class CompositeMatchStrategy
 *
 * Composite strategy that attempts to match a route using multiple match strategies in sequence.
 * If one of the strategies succeeds in matching, the process stops and returns the result.
 * Otherwise, if no strategy matches, it returns null.
 */

namespace Bibo\Mvc\Core\Strategies\Router;

use Bibo\Mvc\Core\Abstracts\AbstractMatchStrategy;

/**
 * Composite strategy that tries multiple match strategies in sequence.
 */
class CompositeMatchStrategy extends AbstractMatchStrategy
{
    /**
     * @var AbstractMatchStrategy[]
     */
    private array $strategies = [];

    /**
     * Constructor method to initialize strategies
     *
     * @param array $strategies An array of strategies to be added
     *
     * @return void
     */
    public function __construct(array $strategies = [])
    {
        foreach ($strategies as $strategy) {
            $this->addStrategy($strategy);
        }
    }

    /**
     * Add a strategy
     *
     * @param  AbstractMatchStrategy $strategy
     * @return void
     */
    public function addStrategy(AbstractMatchStrategy $strategy): void
    {
        $this->strategies[] = $strategy;
    }

    /**
     * Try matching routes using each strategy until one succeeds
     *
     * @param string $method
     * @param string $path
     * @param array  $routes
     *
     * @return array|null
     */
    public function match(string $method, string $path, array $routes): ?array
    {
        foreach ($this->strategies as $strategy) {
            $result = $strategy->match($method, $path, $routes);
            if ($result !== null) {
                return $result;
            }
        }

        return null;
    }
}
