<?php

require_once 'Database.php';
require_once 'Logger.php';

class User
{
    protected $pdo;
    protected $logger;

    protected $id;
    protected $fullName;
    protected $email;

    public function __construct()
    {
        $database = new Database();

        $this->pdo = $database->getConnection();
        $this->logger = new Logger();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM users
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProfile()
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        return $this->getById($_SESSION['user_id']);
    }

    public function visitPage()
    {
        if (isset($_SESSION['user_id'])) {
            $this->logger->addLog($_SESSION['user_id'], 4);
        }
    }
}