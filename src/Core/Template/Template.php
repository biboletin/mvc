<?php

namespace Bibo\Core\Template;

use Bibo\Core\Interfaces\TemplateEngineInterface;

class Template
{
    private TemplateEngineInterface $engine;

    public function __construct(TemplateEngineInterface $engine)
    {
        $this->engine = $engine;
    }

    public function render(string $view, array $parameters = []): string
    {
        return $this->engine->render($view, $parameters);
    }
}
