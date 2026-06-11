<?php

session_start();
include 'database.php';
include 'header.php';

$stmt = $pdo->prepare("
    SELECT
        news.*,
        users.fullName
    FROM news
    INNER JOIN users
        ON news.user_id = users.id
    ORDER BY news.created_at DESC
");

$stmt->execute();

$newsList = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h2>Все новости</h2>

<?php foreach ($newsList as $news): ?>

    <div>

        <h3>
            <?= htmlspecialchars($news['title']) ?>
        </h3>

        <p>
            Автор: <?= htmlspecialchars($news['fullName']) ?>
        </p>

        <?php if (!empty($news['image'])): ?>

            <img
                src="uploads/<?= $news['image'] ?>"
                width="300"
            >

        <?php endif; ?>

        <p>
            <?= htmlspecialchars($news['description']) ?>
        </p>

        <p>
            <?= $news['created_at'] ?>
        </p>

        <?php
        if (
            isset($_SESSION['user_id']) &&
            $_SESSION['user_id'] == $news['user_id']
        ):
        ?>

            <a href="news_edit_delete.php?id=<?= $news['id'] ?>"            >
                Редактировать
            </a>

        <?php endif; ?>

        <hr>

    </div>

<?php endforeach; ?>

<?php include 'footer.php'; ?>