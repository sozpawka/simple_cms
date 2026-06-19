<?php

require_once __DIR__ . '/Database.php';
require_once 'interfaces/Loggable.php';

class Logger implements Loggable
{
    private $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function addLog($userId, $actionId)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO logs
            (user_id, action_id, page, ipAddress)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $userId,
            $actionId,
            basename($_SERVER['PHP_SELF']),
            $_SERVER['REMOTE_ADDR']
        ]);
    }
}