<?php

class Database
{
    private $host = '127.0.0.1';
    private $dbname = 'cms_db';
    private $username = 'root';
    private $password = '';

    private $pdo = null;

    public function getConnection()
    {
        if ($this->pdo == null) {

            try {

                $this->pdo = new PDO(
                    "mysql:host=$this->host;dbname=$this->dbname;charset=utf8mb4",
                    $this->username,
                    $this->password
                );

                $this->pdo->setAttribute(
                    PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_EXCEPTION
                );

            } catch (PDOException $e) {

                die('Ошибка подключения: ' . $e->getMessage());

            }
        }

        return $this->pdo;
    }
}