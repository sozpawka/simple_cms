<?php
require_once __DIR__ . '/Database.php';
require_once 'Logger.php';

class PageLogger
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger();
    }

    public function log()
    {
        if (
            isset($_SESSION['user_id']) &&
            basename($_SERVER['PHP_SELF']) != 'admin.php'
        ) {
            $this->logger->addLog(
                $_SESSION['user_id'],
                4
            );
        }
    }
    public function logLogout($userId)
    {
        $this->logger->addLog($userId, 3); // 3 = logout
    }
}