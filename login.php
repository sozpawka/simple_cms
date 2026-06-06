<?php
include 'database.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("
        SELECT *
        FROM users
        WHERE email = ?
    ");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullName'] = $user['fullName'];
            $_SESSION['role_id'] = $user['role_id'];
            header("Location: index.php");
            exit;
        }
    }
    echo "<p>Неверный email или пароль</p>";
}
include 'header.php';
?>

<h2>Вход в систему</h2>
<form method="post">
    <p>
        Email: <input type="email" name="email" required>
    </p>
    <p>
        Пароль: <input type="password" name="password" required>
    </p>
    <button type="submit">
        Войти
    </button>

</form>

<?php include 'footer.php'; ?>