<?php

session_start();

include 'classes/Auth.php';

$error = '';

$auth = new Auth();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($auth->login($email, $password)) {

        header('Location: index.php');
        exit;

    }

    $error = 'Неверный email или пароль';
}

include 'header.php';

?>

<h2>Вход в систему</h2>

<?php if ($error != ''): ?>
    <p>
        <?php echo $error; ?>
    </p>
<?php endif; ?>

<form method="POST">

    <p>
        Email:
        <input type="email" name="email" required>
    </p>

    <p>
        Пароль:
        <input type="password" name="password" required>
    </p>

    <button type="submit">
        Войти
    </button>

</form>

<?php include 'footer.php'; ?>