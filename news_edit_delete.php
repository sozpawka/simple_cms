<?php

session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die('Не передан ID');
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("
    SELECT *
    FROM news
    WHERE id = ?
");

$stmt->execute([$id]);

$news = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$news) {
    die('Новость не найдена');
}

if ($news['user_id'] != $_SESSION['user_id']) {
    die('Нет доступа');
}

if (isset($_POST['update'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $stmt = $pdo->prepare("
        UPDATE news
        SET title = ?, description = ?
        WHERE id = ?
    ");
    $stmt->execute([
        $title,
        $description,
        $id
    ]);
    header("Location: news.php");
    exit;
}

if (isset($_POST['delete'])) {
    $stmt = $pdo->prepare("
        DELETE FROM news
        WHERE id = ?
    ");
    $stmt->execute([$id]);
    header("Location: news.php");
    exit;
}

include 'header.php';
?>

<h2>Редактирование новости</h2>
<form method="POST">
    <p>
        Заголовок:
        <input
            type="text"
            name="title"
            value="<?= htmlspecialchars($news['title']) ?>"
            required
        >
    </p>
    <p>Описание:</p>
    <textarea
        name="description"
        rows="10"
        cols="50"
        required
    ><?= htmlspecialchars($news['description']) ?></textarea>
    <br><br>
    <button type="submit" name="update">
        Сохранить
    </button>
    <button
        type="submit"
        name="delete"
        onclick="return confirm('Удалить новость?')"
    >
        Удалить
    </button>
</form>
<?php include 'footer.php'; ?>