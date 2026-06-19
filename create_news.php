<?php
session_start();
require_once 'classes/News.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$news = new News();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $news->create(
        $_SESSION['user_id'],
        $_POST['title'],
        $_POST['description'],
        $_FILES['image']
    );

    header('Location: news.php');
    exit;
}
include 'header.php';
?>
<h2>Создать новость</h2>
<form method="POST" enctype="multipart/form-data">
    <p>
        Заголовок: <input type="text" name="title" required>
    </p>

    <p>
        Фото: <input type="file" name="image">
    </p>
    <p>Описание: </p>
    <textarea
        name="description"
        rows="10"
        cols="50"
        required
    ></textarea>
    <br><br>
    <button type="submit">
        Создать
    </button>
</form>
<?php include 'footer.php'; ?>