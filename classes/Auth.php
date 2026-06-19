<?php

require_once 'Database.php';
require_once 'Logger.php';

class Auth
{
    private $pdo;
    private $logger;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();

        $this->logger = new Logger();
    }

    public function login($email, $password)
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM users
            WHERE email = ?
        ");

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullName'] = $user['fullName'];
        $_SESSION['role_id'] = $user['role_id'];

        $this->logger->addLog($user['id'], 2);

        return true;
    }

    public function logout()
    {
        if (isset($_SESSION['user_id'])) {
            $this->logger->addLog($_SESSION['user_id'], 3);
        }

        session_destroy();
    }
}