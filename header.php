<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>3 laba</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <a href="index.php">
        <h1>CMS</h1>
    </a>
    <div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <span>
                Привет,<?php echo htmlspecialchars($_SESSION['fullName']); ?>
            </span>
            <a class="logout" href="logout.php">Выйти</a>

        <?php else: ?>
            <a href="login.php">Войти</a>
            <a href="register.php">Зарегистрироваться</a>
        <?php endif; ?>

    </div>

</header>
<div class="container">
    <nav>
        <a href="news.php">Все новости</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="create_news.php">Создать новость</a>
        <?php endif; ?>
    </nav>
    <hr>