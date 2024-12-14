<?php

require_once './core/Database.php';

class Task {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function store($title, $description, $category_id, $user_id, $start_date, $end_date) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO tasks (title, description, status, category_id, user_id, start_date, end_date)
                VALUES (:title, :description, 'Pending', :category_id, :user_id, :start_date, :end_date)
            ");
    
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
    
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getAll($user_id) {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM tasks 
                WHERE user_id = :user_id AND deleted_at IS NULL
            ");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getById($task_id) {
        try {
            $stmt = $this->db->prepare("
                SELECT tasks.*, categories.id as category_id, categories.name as category_name
                FROM tasks
                LEFT JOIN categories ON tasks.category_id = categories.id
                WHERE tasks.id = :task_id AND tasks.deleted_at IS NULL
            ");
            $stmt->bindParam(':task_id', $task_id);
            $stmt->execute();
    
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function update($task_id, $title, $description, $category_id, $start_date, $end_date) {
        try {
            $stmt = $this->db->prepare("
                UPDATE tasks 
                SET title = :title, description = :description,  
                category_id = :category_id,
                start_date = :start_date,
                end_date = :end_date
                WHERE id = :task_id AND deleted_at IS NULL
            ");

            $stmt->bindParam(':task_id', $task_id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->bindParam(':category_id', $category_id);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function complete($task_id) {
        try {
            $stmt = $this->db->prepare("
                UPDATE tasks 
                SET status = 'Completed' 
                WHERE id = :task_id AND deleted_at IS NULL
            ");
    
            $stmt->bindParam(':task_id', $task_id);
    
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    } 

    public function softDelete($task_id) {
        try {
            $stmt = $this->db->prepare("
                UPDATE tasks 
                SET deleted_at = NOW() 
                WHERE id = :task_id
            ");

            $stmt->bindParam(':task_id', $task_id);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function pin($task_id) {
        try {
            $stmt = $this->db->prepare("
                SELECT is_pinned FROM tasks 
                WHERE id = :task_id AND deleted_at IS NULL
            ");
            $stmt->bindParam(':task_id', $task_id);
            $stmt->execute();
    
            $task = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$task) {
                return ['status' => 'error', 'message' => 'Task not found or already deleted'];
            }
    
            $newStatus = $task['is_pinned'] ? 0 : 1;
    
            $updateStmt = $this->db->prepare("
                UPDATE tasks 
                SET is_pinned = :newStatus 
                WHERE id = :task_id AND deleted_at IS NULL
            ");
            $updateStmt->bindParam(':newStatus', $newStatus);
            $updateStmt->bindParam(':task_id', $task_id);
            $updateStmt->execute();
    
            return [
                'status' => 'success',
                'is_pinned' => $newStatus,
            ];
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return ['status' => 'error', 'message' => 'Server error'];
        }
    } 
}
