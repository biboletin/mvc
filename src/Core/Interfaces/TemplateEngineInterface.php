<?php

namespace Bibo\Core\Interfaces;

/**
 *
 */
interface TemplateEngineInterface
{
    /**
     * @param string $template
     * @param array  $data
     *
     * @return string
     */
    public function render(string $template, array $data = []): string;
}
