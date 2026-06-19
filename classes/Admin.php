<?php
require_once __DIR__ . '/Database.php';
require_once 'User.php';

class Admin extends User
{
    public function getUsers()
    {
        $stmt = $this->pdo->query("
            SELECT
                id,
                fullName
            FROM users
            ORDER BY fullName
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLogs($userId = '', $action = '', $dateFrom = '', $dateTo = '')
    {
        $sql = "
            SELECT
                logs.id,
                users.id AS userId,
                users.fullName,
                users.email,
                log_actions.actionName,
                logs.page,
                logs.ipAddress,
                logs.created_at
            FROM logs
            JOIN users
                ON logs.user_id = users.id
            JOIN log_actions
                ON logs.action_id = log_actions.id
            WHERE 1=1
        ";

        $params = [];

        if ($userId != '') {
            $sql .= " AND users.id = ?";
            $params[] = $userId;
        }

        if ($action != '') {
            $sql .= " AND log_actions.actionName = ?";
            $params[] = $action;
        }
        if ($dateFrom != '') {
            $sql .= " AND DATE(logs.created_at) >= ?";
            $params[] = $dateFrom;
        }

        if ($dateTo != '') {
            $sql .= " AND DATE(logs.created_at) <= ?";
            $params[] = $dateTo;
        }
        $sql .= "
            ORDER BY
            users.fullName,
            logs.created_at DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $groupedLogs = [];

        foreach ($logs as $log) {

            $groupedLogs[$log['userId']]['name'] =
                $log['fullName'];

            $groupedLogs[$log['userId']]['email'] =
                $log['email'];

            $groupedLogs[$log['userId']]['logs'][] =
                $log;
        }

        return $groupedLogs;
    }

    public function deleteLogsByUser($userId)
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM logs
            WHERE user_id = ?
        ");

        return $stmt->execute([$userId]);
    }

    public function getPageStatistics()
    {
        $stmt = $this->pdo->query("
            SELECT
                page,
                COUNT(*) AS visits
            FROM logs
            WHERE action_id = 4
            GROUP BY page
            ORDER BY visits DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function exportLogs($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT
                users.fullName,
                users.email,
                log_actions.actionName,
                logs.page,
                logs.ipAddress,
                logs.created_at
            FROM logs
            JOIN users
                ON logs.user_id = users.id
            JOIN log_actions
                ON logs.action_id = log_actions.id
            WHERE users.id = ?
            ORDER BY logs.created_at DESC
        ");

        $stmt->execute([$userId]);

        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$logs) {
            return false;
        }

        $fileName =
            'logs/user_' .
            $userId .
            '_' .
            date('Y-m-d_H-i-s') .
            '.txt';

        $content = '';

        $content .=
            'Пользователь: ' .
            $logs[0]['fullName'] .
            PHP_EOL;

        $content .=
            'Email: ' .
            $logs[0]['email'] .
            PHP_EOL;

        $content .=
            str_repeat('=', 70) .
            PHP_EOL .
            PHP_EOL;

        foreach ($logs as $log) {

            $content .=
                'Действие: ' .
                $log['actionName'] .
                PHP_EOL;

            $content .=
                'Страница: ' .
                $log['page'] .
                PHP_EOL;

            $content .=
                'IP: ' .
                $log['ipAddress'] .
                PHP_EOL;

            $content .=
                'Дата: ' .
                date(
                    'd.m.Y H:i:s',
                    strtotime($log['created_at'])
                ) .
                PHP_EOL;

            $content .=
                str_repeat('-', 50) .
                PHP_EOL;
        }

        file_put_contents(
            $fileName,
            $content
        );

        return $fileName;
    }

}