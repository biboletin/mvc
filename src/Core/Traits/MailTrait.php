<?php

namespace Biboletin\Mvc\Core\Traits;

trait MailTrait
{
    private string $from;
    private array $recipients;
    private string $subject;
    private string $message;

    public function setFrom(string $from): void
    {
        $this->from = $from;
    }
    public function setRecipients(array $recipients): void
    {
        $this->recipients = $recipients;
    }
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getFrom(): string
    {
        return $this->from;
    }
    public function getRecipients(): array
    {
        return $this->recipients;
    }
    public function getSubject(): string
    {
        return $this->subject;
    }
    public function getMessage(): string
    {
        return $this->message;
    }
}
