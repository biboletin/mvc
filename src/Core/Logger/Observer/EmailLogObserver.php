<?php

namespace Bibo\Mvc\Core\Logger\Observer;

use Bibo\Mvc\Core\Logger\Interfaces\LogObserverInterface;
use Stringable;

class EmailLogObserver implements LogObserverInterface
{
    public function update(string $level, Stringable|string $message, array $context = []): void
    {
        // Here you would implement the logic to send an email with the log message.
        // This is a placeholder implementation.
        $emailSubject = "Log Notification: {$level}";
        $emailBody = "Message: {$message}\nContext: " . json_encode($context);

        echo "Sending email with subject: {$emailSubject}\n";

        // mail('', $emailSubject, $emailBody);
    }
}
