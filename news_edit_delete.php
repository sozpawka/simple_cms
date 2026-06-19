<?php

session_start();

require_once __DIR__ . '/classes/News.php';
require_once __DIR__ . '/classes/PageLogger.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$newsClass = new News();

$id = (int)$_GET['id'];

$news = $newsClass->getById($id);

if (!$news) {
    die('Новость не найдена');
}

if ($news['user_id'] != $_SESSION['user_id']) {
    die('Нет доступа');
}

if (isset($_POST['update'])) {

    $newsClass->update(
        $id,
        $_POST['title'],
        $_POST['description'],
        $_FILES['image']
    );

    header('Location: news.php');
    exit;
}

if (isset($_POST['delete'])) {

    $newsClass->delete($id);

    header('Location: news.php');
    exit;
}

include 'header.php';
?>

<h2>Редактирование новости</h2>
<form method="POST" enctype="multipart/form-data">
    <p>
        Заголовок:
        <input
            type="text"
            name="title"
            value="<?php echo htmlspecialchars($news['title']); ?>"
            required
        >
    </p>
    <?php if (!empty($news['image'])): ?>
        <p>
            <img src="uploads/<?php echo htmlspecialchars($news['image']); ?>" width="300">
        </p>
    <?php endif; ?>
    <p>
        Новое изображениея:
        <input type="file" name="image">
    </p>
    <p>Описание:</p>
    <textarea
        name="description"
        rows="10"
        cols="50"
        required
    ><?php echo htmlspecialchars($news['description']); ?></textarea>
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