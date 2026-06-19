<?php

session_start();

require_once 'classes/Admin.php';

if (!isset($_SESSION['user_id'])) {
    die('Нет доступа');
}

if ($_SESSION['role_id'] != 3) {
    die('Нет доступа');
}

$admin = new Admin();

$fileName = $admin->exportLogs(
    (int)$_GET['user_id']
);

if (!$fileName) {
    die('Логи не найдены');
}

header('Content-Type: text/plain');

header(
    'Content-Disposition: attachment; filename="' .
    basename($fileName) .
    '"'
);

readfile($fileName);

exit;