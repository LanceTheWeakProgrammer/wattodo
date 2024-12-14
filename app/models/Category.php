<?php

require_once './core/Database.php';

class Category {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function store($name, $description, $user_id) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO categories (name, description, user_id) 
                VALUES (:name, :description, :user_id)
            ");

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':user_id', $user_id);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getAll($user_id) {
        try {
            $stmt = $this->db->prepare("
                SELECT id, name, description 
                FROM categories 
                WHERE user_id = :user_id AND deleted_at IS NULL
            ");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($categories as &$category) {
                $stmt = $this->db->prepare("
                    SELECT id, title, description, status, category_id, start_date, end_date, is_pinned
                    FROM tasks 
                    WHERE category_id = :category_id AND deleted_at IS NULL
                    ORDER BY is_pinned DESC, start_date ASC
                ");
                $stmt->bindParam(':category_id', $category['id'], PDO::PARAM_INT);
                $stmt->execute();
                $category['tasks'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            return $categories;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function update($category_id, $name, $description) {
        try {
            $stmt = $this->db->prepare("
                UPDATE categories 
                SET name = :name, description = :description 
                WHERE id = :category_id AND deleted_at IS NULL
            ");

            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function softDelete($category_id) {
        try {
            $stmt = $this->db->prepare("
                UPDATE categories 
                SET deleted_at = NOW() 
                WHERE id = :category_id
            ");

            $stmt->bindParam(':category_id', $category_id);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}

