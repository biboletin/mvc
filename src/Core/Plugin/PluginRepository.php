<?php

namespace Bibo\Mvc\Core\Plugin;

class PluginRepository
{
    public function __construct()
    {
    }

    public function getAllPlugins(): array
    {
        return [];
    }

    protected function getPluginClassFromFile(string $pluginFile): string
    {
        $pluginName = basename(dirname($pluginFile));

        return 'Plugin\\' . ucfirst($pluginName) . '';
    }
}
