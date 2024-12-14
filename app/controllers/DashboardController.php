<?php

require_once './app/models/Dashboard.php';
require_once 'Controller.php';

class DashboardController extends Controller {
    private $dashboard;

    public function __construct() {
        parent::__construct();
        $this->dashboard = new Dashboard();
    }

    public function stats($user_id) {
        try {
            $dashboardStats = $this->dashboard->stats($user_id);
            return json_encode([
                'status' => 'success',
                'data' => $dashboardStats,
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return json_encode([
                'status' => 'error',
                'message' => 'Server error',
            ]);
        }
    }
}
