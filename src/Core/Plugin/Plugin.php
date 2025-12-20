<?php

namespace Bibo\Mvc\Core\Plugin;

use Bibo\Mvc\Core\Application\App;
use Bibo\Mvc\Core\Traits\DescriptionAwareTrait;
use Bibo\Mvc\Core\Traits\EnabledAwareTrait;
use Bibo\Mvc\Core\Traits\NameAwareTrait;
use Bibo\Mvc\Core\Traits\VersionAwareTrait;

class Plugin
{
    use NameAwareTrait;
    use EnabledAwareTrait;
    use DescriptionAwareTrait;
    use VersionAwareTrait;

    public function register(App $app): void
    {
    }

    public function boot(App $app): void
    {
    }
}
