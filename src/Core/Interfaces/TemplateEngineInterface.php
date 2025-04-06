<?php

namespace Bibo\Core\Interfaces;

/**
 * Interface TemplateEngineInterface
 *
 * @package Bibo\Core\Interfaces
 */
interface TemplateEngineInterface
{
    /**
     * Render a template with the given data.
     *
     * @param string $template
     * @param array  $data
     *
     * @return string
     */
    public function render(string $template, array $data = []): string;
}
