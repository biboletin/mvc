<?php

namespace Bibo\Mvc\Core\Interfaces;

/**
 * Interface AjaxInterface
 *
 * Defines a contract for handling AJAX requests.
 */
interface AjaxInterface
{
    /**
     * Handle the AJAX request.
     *
     * @return void
     */
    public function handle(): void;
}
