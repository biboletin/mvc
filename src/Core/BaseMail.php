<?php

namespace Biboletin\Mvc\Core;

use Biboletin\Mvc\Core\Traits\MailTrait;

class BaseMail
{
    use MailTrait;

    public function __construct()
    {
    }

    public function send(): void
    {
        if (empty($this->getRecipients())) {
            //
        }

        foreach ($this->getRecipients() as $recipient) {
            //
        }
    }

    public function __destruct()
    {
    }
}
