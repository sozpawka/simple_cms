<?php

session_start();

require_once __DIR__ . '/classes/News.php';
require_once __DIR__ . '/classes/PageLogger.php';

$newsClass = new News();

if (
    isset($_GET['delete']) &&
    isset($_SESSION['role_id']) &&
    $_SESSION['role_id'] == 3
) {
    $newsClass->delete((int)$_GET['delete']);
    header('Location: news.php');
    exit;
}

$newsList = $newsClass->getAll();

include 'header.php';

?>

<h2>Все новости</h2>

<?php foreach ($newsList as $news): ?>

    <div class="news-card">

        <h3>
            <?php echo htmlspecialchars($news['title']); ?>
        </h3>

        <p>
            Автор:
            <?php echo htmlspecialchars($news['fullName']); ?>
        </p>

        <?php if (!empty($news['image'])): ?>

            <img
                src="uploads/<?php echo htmlspecialchars($news['image']); ?>"
                width="300"
            >

        <?php endif; ?>

        <p>
            <?php echo htmlspecialchars($news['description']); ?>
        </p>

        <p>
            <?php echo $news['created_at']; ?>
        </p>

        <?php if (
            isset($_SESSION['user_id']) &&
            $_SESSION['user_id'] == $news['user_id']
        ): ?>

            <a href="news_edit_delete.php?id=<?php echo $news['id']; ?>">
                Редактировать
            </a>

        <?php endif; ?>

        <?php if (
            isset($_SESSION['role_id']) &&
            $_SESSION['role_id'] == 3
        ): ?>
            <a href="news.php?delete=<?php echo $news['id']; ?>" onclick="return confirm('Удалить новость?')">Удалить</a>
        <?php endif; ?>

        <hr>

    </div>

<?php endforeach; ?>

<?php include 'footer.php'; ?>