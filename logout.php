<?php
session_start();

require_once __DIR__ . '/classes/Logger.php';

if (isset($_SESSION['user_id'])) {

    $logger = new Logger();
    $logger->addLog($_SESSION['user_id'], 3); // 3 = logout

    session_unset();
    session_destroy();
}

header("Location: login.php");
exit;