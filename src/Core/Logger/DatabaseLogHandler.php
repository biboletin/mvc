<?php

namespace Bibo\Mvc\Core\Logger;

use Bibo\Mvc\Core\Interfaces\LogHandlerInterface;
use PDO;

class DatabaseLogHandler implements LogHandlerInterface
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function write(string $level, string $message, array $context = []): void
    {
        $date = date('Y-m-d H:i:s');
        $query = 'INSERT INTO logs (date, level, message, context) VALUES (:date, :level, :message, :context)';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':date' => $date,
            ':level' => $level,
            ':message' => $message,
            ':context' => json_encode($context),
        ]);
    }
}
