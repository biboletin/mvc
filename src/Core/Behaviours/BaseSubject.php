<?php

namespace Biboletin\Mvc\Core\Behaviours;

use SplSubject;
use SplObserver;

class BaseSubject implements SplSubject
{
    public function attach(SplObserver $observer): void
    {
        // TODO: Implement attach() method.
    }

    public function detach(SplObserver $observer): void
    {
        // TODO: Implement detach() method.
    }

    public function notify(): void
    {
        // TODO: Implement notify() method.
    }
}
