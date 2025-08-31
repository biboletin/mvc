<?php

namespace Bibo\Mvc\Core\Traits;

trait PrefixAwareTrait
{
    /**
     * Prefix
     *
     * @var string
     */
    protected string $prefix;

    /**
     * Set prefix
     *
     * @param string $prefix
     *
     * @return void
     */
    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }

    /**
     * Get prefix
     *
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }
}
