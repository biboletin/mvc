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
use Bibo\Mvc\Core\Interfaces\RouteMatchingStrategyInterface;
use Bibo\Mvc\Core\Router\MatchedRoute;

/**
 * Composite strategy that tries multiple match strategies in sequence.
 */
class CompositeMatchStrategy extends AbstractMatchStrategy
{
    /**
     * Array of strategies
     *
     * @var RouteMatchingStrategyInterface[]
     */
    private array $strategies = [];

    /**
     * Constructor method to initialize strategies
     *
     * @param array $strategies An array of strategies to be added
     *
     * @return void
     */
    public function __construct(iterable $strategies)
    {
        foreach ($strategies as $strategy) {
            $this->addStrategy($strategy);
        }
    }

    /**
     * Add a strategy
     *
     * @param AbstractMatchStrategy $strategy
     *
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
     * @return MatchedRoute|null
     */
    public function match(string $method, string $path, array $routes): ?MatchedRoute
    {
        foreach ($this->strategies as $strategy) {
            $matched = $strategy->match($method, $path, $routes);
            if ($matched !== null) {
                return $matched;
            }
        }

        return null;
    }
}
