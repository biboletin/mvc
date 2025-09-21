<?php

namespace Bibo\Mvc\Core\Traits;

/**
 * Compression aware trait
 */
trait CompressAwareTrait
{
    /**
     * Compressed flag
     *
     * @var bool
     */
    protected bool $compressed = false;

    /**
     * Set compression flag
     *
     * @param bool $compress
     *
     * @return void
     */
    public function setCompressed(bool $compress): void
    {
        $this->compressed = $compress;
    }

    /**
     * Check if is compressed
     *
     * @return bool
     */
    public function isCompressed(): bool
    {
        return $this->compressed;
    }

    /**
     * Get compressed flag
     *
     * @return bool
     */
    public function getCompressed(): bool
    {
        return $this->compressed;
    }
}
