<?php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/../interfaces/NewsInterface.php';
require_once __DIR__ . '/../traits/ImageUploader.php';

class News implements Newsinterface
{
    use ImageUploader;

    private $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function create($userId, $title, $description, $image)
    {
        $imageName = $this->uploadImage($image);

        $stmt = $this->pdo->prepare("
            INSERT INTO news (user_id, title, image, description)
            VALUES (?, ?, ?, ?)
        ");

        return $stmt->execute([
            $userId,
            $title,
            $imageName,
            $description
        ]);
    }

    public function update($id, $title, $text)
    {
        $stmt = $this->pdo->prepare("
            UPDATE news
            SET title = ?, description = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $title,
            $text,
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM news
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("
            SELECT news.*, users.fullName
            FROM news
            JOIN users ON news.user_id = users.id
            ORDER BY news.id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM news
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}