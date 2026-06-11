<?php
session_start();
include 'database.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $imageName = '';
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            'uploads/' . $imageName
        );
    }
    $stmt = $pdo->prepare("
        INSERT INTO news
        (user_id, title, image, description)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([
        $_SESSION['user_id'],
        $title,
        $imageName,
        $description
    ]);
    header("Location: news.php");
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

    <p>
        Описание:
    </p>
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