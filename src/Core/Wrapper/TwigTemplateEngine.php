<?php

namespace Bibo\Core\Wrapper;

use Bibo\Core\Interfaces\TemplateEngineInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class TwigTemplateEngine implements TemplateEngineInterface
{
    protected Environment $twig;

    public function __construct(string $views, ?string $cachePath = null, bool $debug = false)
    {
        $loader = new FilesystemLoader($views);
        $options = [
            'debug' => $debug,
            'auto_reload' => $debug,
        ];

        if ($cachePath) {
            $options['cache'] = $cachePath;
        }

        $this->twig = new Environment($loader, $options);

        if ($debug) {
            $this->twig->addExtension(new DebugExtension());
        }
    }

    /**
     * Render template
     *
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function render(string $template, array $data = []): string
    {
        return $this->twig->render($template, $data);
    }
}
