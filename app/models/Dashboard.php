<?php

require_once './core/Database.php';

class Dashboard {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function stats($user_id) {
        try {
            $stmtPinned = $this->db->prepare("
                SELECT * FROM tasks
                WHERE user_id = :user_id AND is_pinned = 1 AND deleted_at IS NULL
            ");
            $stmtPinned->bindParam(':user_id', $user_id);
            $stmtPinned->execute();
            $pinnedTasks = $stmtPinned->fetchAll(PDO::FETCH_ASSOC);

            $stmtToday = $this->db->prepare("
                SELECT * FROM tasks
                WHERE user_id = :user_id AND DATE(start_date) = CURDATE() AND deleted_at IS NULL
            ");
            $stmtToday->bindParam(':user_id', $user_id);
            $stmtToday->execute();
            $tasksToday = $stmtToday->fetchAll(PDO::FETCH_ASSOC);

            $stmtCompare = $this->db->prepare("
                SELECT status, COUNT(*) AS count
                FROM tasks
                WHERE user_id = :user_id AND deleted_at IS NULL
                GROUP BY status
            ");
            $stmtCompare->bindParam(':user_id', $user_id);
            $stmtCompare->execute();
            $results = $stmtCompare->fetchAll(PDO::FETCH_ASSOC);

            $stats = [
                'Completed' => 0,
                'Pending' => 0
            ];
            foreach ($results as $row) {
                if (isset($stats[$row['status']])) {
                    $stats[$row['status']] = $row['count'];
                }
            }

            return [
                'pinned_tasks' => $pinnedTasks,
                'tasks_today' => $tasksToday,
                'task_stats' => $stats
            ];
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [
                'pinned_tasks' => [],
                'tasks_today' => [],
                'task_stats' => [
                    'Completed' => 0,
                    'Pending' => 0
                ]
            ];
        }
    }
}
