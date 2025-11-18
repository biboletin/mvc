<?php

namespace Bibo\Mvc\Core\Traits\Http;

/**
 * Unified helpers for retrieving input data from query string and parsed body.
 *
 * This trait expects the consumer to provide:
 * - getQueryParams(): array
 * - getParsedBody(): array|object|null
 */
trait InputHelper
{
    /**
     * Get a value from the query string.
     *
     * @param  string $key The query parameter name.
     * @param  mixed  $default Default value if not set.
     * @return mixed The parameter value or default.
     */
    public function getQueryParam(string $key, $default = null): mixed
    {
        $query = $this->getQueryParams();

        return $query[$key] ?? $default;
    }

    /**
     * Return all query string parameters.
     *
     * @return array<string, mixed>
     */
    public function getAllQueryParams(): mixed
    {
        return $this->getQueryParams();
    }

    /**
     * Get a value from the parsed request body (e.g., application/x-www-form-urlencoded or JSON).
     *
     * @param  string $key The body parameter name.
     * @param  mixed  $default Default value if not set.
     * @return mixed The parameter value or default.
     */
    public function getParsedBodyParam(string $key, $default = null): mixed
    {
        $body = $this->getParsedBody();

        if (is_array($body)) {
            return $body[$key] ?? $default;
        }

        return $default;
    }

    /**
     * Return all parsed body parameters as an array.
     *
     * @return array<string, mixed>
     */
    public function getAllBodyParams(): array
    {
        $body = $this->getParsedBody();

        return is_array($body) ? $body : [];
    }

    /**
     * Retrieve a value from either the query string or the parsed body.
     * Query string takes precedence.
     *
     * @param  string $key The input key.
     * @param  mixed  $default Default value if not set in either place.
     * @return mixed
     */
    public function getInput(string $key, $default = null): mixed
    {
        $value = $this->getQueryParam($key, null);
        if ($value !== null) {
            return $value;
        }

        return $this->getParsedBodyParam($key, $default);
    }

    /**
     * Determine whether the input key exists in query or body parameters.
     */
    public function hasInput(string $key): bool
    {
        return $this->getQueryParam($key, null) !== null
            || $this->getParsedBodyParam($key, null) !== null;
    }

    /**
     * Return only the specified keys from combined query+body parameters.
     *
     * @param  string[] $keys Keys to include.
     * @return array<string, mixed>
     */
    public function only(array $keys): array
    {
        $all = array_merge($this->getAllQueryParams(), $this->getAllBodyParams());

        return array_intersect_key($all, array_flip($keys));
    }

    /**
     * Return all combined inputs except the specified keys.
     *
     * @param  string[] $keys Keys to exclude.
     * @return array<string, mixed>
     */
    public function except(array $keys): array
    {
        $all = array_merge($this->getAllQueryParams(), $this->getAllBodyParams());
        foreach ($keys as $key) {
            unset($all[$key]);
        }

        return $all;
    }
}
