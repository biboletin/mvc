<?php

namespace Bibo\Mvc\Core\Traits;

trait EncryptedAwareTrait
{
    /**
     * Encryption flag
     *
     * @var bool
     */
    protected bool $encrypted;

    /**
     * Set encrypted flag
     *
     * @param bool $encrypted
     *
     * @return void
     */
    public function setEncrypted(bool $encrypted): void
    {
        $this->encrypted = $encrypted;
    }

    /**
     * Check if the value is encrypted
     *
     * @return bool
     */
    public function isEncrypted(): bool
    {
        return $this->encrypted;
    }

    /**
     * Get encrypted flag
     *
     * @return bool
     */
    public function getEncrypted(): bool
    {
        return $this->encrypted;
    }
}
