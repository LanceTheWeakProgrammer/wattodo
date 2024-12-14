<?php

require_once './app/models/Category.php';
require_once 'Controller.php';

class CategoryController extends Controller {
    private $category;

    public function __construct() {
        parent::__construct();
        $this->category = new Category();
    }

    public function store($name, $description, $user_id) {
        try {
            if ($this->category->store($name, $description, $user_id)) {
                return json_encode([
                    'status' => 'success',
                    'message' => 'Category created successfully',
                ]);
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Failed to create category',
                ]);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return json_encode([
                'status' => 'error',
                'message' => 'Server error',
            ]);
        }
    }

    public function getAll($user_id) {
        try {
            $categories = $this->category->getAll($user_id);
            return json_encode([
                'status' => 'success',
                'data' => $categories,
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return json_encode([
                'status' => 'error',
                'message' => 'Server error',
            ]);
        }
    }

    public function update($category_id, $name, $description) {
        try {
            if ($this->category->update($category_id, $name, $description)) {
                return json_encode([
                    'status' => 'success',
                    'message' => 'Category updated successfully',
                ]);
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Failed to update category',
                ]);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return json_encode([
                'status' => 'error',
                'message' => 'Server error',
            ]);
        }
    }

    public function softDelete($category_id) {
        try {
            if ($this->category->softDelete($category_id)) {
                return json_encode([
                    'status' => 'success',
                    'message' => 'Category deleted successfully',
                ]);
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Failed to delete category',
                ]);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return json_encode([
                'status' => 'error',
                'message' => 'Server error',
            ]);
        }
    }
}
