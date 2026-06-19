<?php

session_start();

require_once __DIR__ . '/classes/Admin.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['role_id'] != 3) {
    die('Доступ запрещен');
}

$admin = new Admin();

if (isset($_GET['clear_user'])) {
    $admin->deleteLogsByUser((int)$_GET['clear_user']);
    header('Location: admin.php');
    exit;
}

$userFilter = $_GET['user_id'] ?? '';
$actionFilter = $_GET['action'] ?? '';

$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';

$logs = $admin->getLogs($userFilter, $actionFilter, $dateFrom, $dateTo);
$users = $admin->getUsers();
$stats = $admin->getPageStatistics();

include 'header.php';
?>

<h2>Логи пользователей</h2>

<form method="GET">
    <label>Пользователь:</label>
    <select name="user_id">
        <option value="">Все</option>
        <?php foreach ($users as $user): ?>
            <option value="<?= $user['id'] ?>" <?= $userFilter == $user['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($user['fullName']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Действие:</label>
    <select name="action">
        <option value="">Все</option>
        <option value="register" <?= $actionFilter == 'register' ? 'selected' : '' ?>>register</option>
        <option value="login" <?= $actionFilter == 'login' ? 'selected' : '' ?>>login</option>
        <option value="logout" <?= $actionFilter == 'logout' ? 'selected' : '' ?>>logout</option>
        <option value="page_visit" <?= $actionFilter == 'page_visit' ? 'selected' : '' ?>>page_visit</option>
    </select>

    <label>Дата от:</label>
    <input type="date" name="date_from" value="<?= $dateFrom ?>">

    <label>Дата до:</label>
    <input type="date" name="date_to" value="<?= $dateTo ?>">
    <button type="submit">Фильтр</button>
    <a href="admin.php">Сбросить</a>
</form>

<hr>

<h2>Список логов</h2>

<?php if (!empty($logs)): ?>

    <?php foreach ($logs as $userId => $userData): ?>

        <h3>
            <?= htmlspecialchars($userData['name']) ?>
            (<?= htmlspecialchars($userData['email']) ?>)
        </h3>

        <table border="1" cellpadding="5">
            <tr>
                <th>Действие</th>
                <th>Страница</th>
                <th>IP</th>
                <th>Дата</th>
            </tr>

            <?php foreach ($userData['logs'] as $log): ?>
                <tr>
                    <td><?= htmlspecialchars($log['actionName']) ?></td>
                    <td><?= htmlspecialchars($log['page']) ?></td>
                    <td><?= htmlspecialchars($log['ipAddress']) ?></td>
                    <td><?= htmlspecialchars($log['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>

        </table>

        <div class="form-actions">
            <a class="btn btn-export" href="export_logs.php?user_id=<?= $userId ?>">
                Экспорт
            </a>
            <a class="btn btn-danger" href="admin.php?clear_user=<?= $userId ?>" onclick="return confirm('Удалить логи?')">
                Очистить
            </a>
        </div>

        <hr>

    <?php endforeach; ?>

<?php else: ?>
    <p>Логи не найдены</p>
<?php endif; ?>

<h2>Статистика посещений</h2>

<table border="1" cellpadding="5">
    <tr>
        <th>Страница</th>
        <th>Посещения</th>
    </tr>

    <?php foreach ($stats as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['page']) ?></td>
            <td><?= $row['visits'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include 'footer.php'; ?>