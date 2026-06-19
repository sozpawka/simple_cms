<?php
require_once __DIR__ . '/classes/Database.php';
$database = new Database();
$pdo = $database->getConnection();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("
        INSERT INTO users (fullName, email, password)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([
        $fullName,
        $email,
        $password
    ]);
    header("Location: login.php");
    exit;
}
include 'header.php';
?>
<h2>Регистрация</h2>
<form method="post">
    <p>
        ФИО: <input type="text" name="fullName" required>
    </p>
    <p>
        Email: <input type="email" name="email" required>
    </p>
    <p>
        Пароль: <input type="password" name="password" required>
    </p>
    <button type="submit">
        Зарегистрироваться
    </button>
</form>
<?php include 'footer.php'; ?>