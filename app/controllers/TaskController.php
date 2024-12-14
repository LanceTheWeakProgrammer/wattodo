<?php

require_once './app/models/Task.php';
require_once './app/validators/TaskValidator.php';
require_once 'Controller.php';

class TaskController extends Controller {
    private $task;

    public function __construct() {
        parent::__construct();
        $this->task = new Task();
    }

    public function store($title, $description, $category_id, $user_id, $start_date, $end_date) {
        try {
            $errors = TaskValidator::validate([
                'title' => $title,
                'description' => $description,
                'category_id' => $category_id,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]);
    
            if (!empty($errors)) {
                return json_encode([
                    'status' => 'error',
                    'message' => $errors,
                ]);
            }
    
            if ($this->task->store($title, $description, $category_id, $user_id, $start_date, $end_date)) {
                return json_encode([
                    'status' => 'success',
                    'message' => 'Task created successfully',
                ]);
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Failed to create task',
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
            $tasks = $this->task->getAll($user_id);
            return json_encode([
                'status' => 'success',
                'data' => $tasks,
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return json_encode([
                'status' => 'error',
                'message' => 'Server error',
            ]);
        }
    }

    public function getById($task_id) {
        try {
            $task = $this->task->getById($task_id);
            if ($task) {
                return json_encode([
                    'status' => 'success',
                    'data' => $task,
                ]);
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Task not found',
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

    public function update($task_id, $title, $description, $category_id, $start_date, $end_date) {
        try {
            if ($this->task->update($task_id, $title, $description, $category_id, $start_date, $end_date)) {
                return json_encode([
                    'status' => 'success',
                    'message' => 'Task updated successfully',
                ]);
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Failed to update task',
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

    public function complete($task_id) {
        try {
            if ($this->task->complete($task_id)) {
                return json_encode([
                    'status' => 'success',
                    'message' => 'Task marked as completed successfully',
                ]);
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Failed to mark task as completed',
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

    public function softDelete($task_id) {
        try {
            if ($this->task->softDelete($task_id)) {
                return json_encode([
                    'status' => 'success',
                    'message' => 'Task deleted successfully',
                ]);
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Failed to delete task',
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

    public function pin($task_id) {
        try {
            $result = $this->task->pin($task_id);
    
            if ($result['status'] === 'success') {
                return json_encode([
                    'status' => 'success',
                    'is_pinned' => $result['is_pinned'],
                    'message' => 'Task pin status toggled successfully',
                ]);
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' => $result['message'], 
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
