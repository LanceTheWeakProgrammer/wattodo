<?php

session_start();

require_once 'core/Router.php';
require_once 'app/controllers/AuthController.php';
require_once 'app/controllers/TaskController.php';
require_once 'app/controllers/CategoryController.php';
require_once 'app/controllers/DashboardController.php'; 
require_once 'app/controllers/ProfileController.php';
require_once 'app/middleware/Authenticate.php';

require_once 'core/Database.php';

$router = new Router();
$authController = new AuthController();
$taskController = new TaskController();
$categoryController = new CategoryController();
$dashboardController = new DashboardController(); 
$profileController = new ProfileController();

$router->add('store', function () use ($authController) {
    echo $authController->store($_POST['username'], $_POST['name'], $_POST['password']);
});

$router->add('authenticate', function () use ($authController) {
    echo $authController->authenticate($_POST['username'], $_POST['password']);
});

$router->add('getUserId', function () {
    Authenticate::checkAuth();
    echo json_encode(['user_id' => $_SESSION['user_id'] ?? null]);
});

$router->add('logout', function () use ($authController) {
    echo $authController->destroy();
});

// Task Routes
$router->add('tasks/store', function () use ($taskController) {
    Authenticate::checkAuth();

    $input = json_decode(file_get_contents('php://input'), true); 
    echo $taskController->store(
        $input['title'] ?? null,
        $input['description'] ?? null,
        $input['category_id'] ?? null,
        $_SESSION['user_id'],
        $input['start_date'] ?? null,
        $input['end_date'] ?? null
    );
});

$router->add('tasks/update', function () use ($taskController) {
    Authenticate::checkAuth();

    $input = json_decode(file_get_contents('php://input'), true); 
    echo $taskController->update(
        $input['task_id'] ?? null,
        $input['title'] ?? null,
        $input['description'] ?? null,
        $input['category_id'] ?? null,
        $input['start_date'] ?? null,
        $input['end_date'] ?? null
    );
});

$router->add('tasks', function () use ($taskController) {
    Authenticate::checkAuth();
    echo $taskController->getAll($_SESSION['user_id']);
});

$router->add('tasks/complete', function () use ($taskController) {
    Authenticate::checkAuth();
    echo $taskController->complete($_POST['task_id']);
});

$router->add('tasks/delete', function () use ($taskController) {
    Authenticate::checkAuth();
    echo $taskController->softDelete($_POST['task_id']);
});

$router->add('tasks/pin', function () use ($taskController) {
    Authenticate::checkAuth();
    echo $taskController->pin($_POST['task_id']);
});

// Category Routes
$router->add('categories/store', function () use ($categoryController) {
    Authenticate::checkAuth();
    echo $categoryController->store(
        $_POST['name'],
        $_POST['description'],
        $_SESSION['user_id']
    );
});

$router->add('categories', function () use ($categoryController) {
    Authenticate::checkAuth();
    echo $categoryController->getAll($_SESSION['user_id']);
});

$router->add('categories/update', function () use ($categoryController) {
    Authenticate::checkAuth();
    echo $categoryController->update(
        $_POST['category_id'],
        $_POST['name'],
        $_POST['description']
    );
});

$router->add('categories/delete', function () use ($categoryController) {
    Authenticate::checkAuth();
    echo $categoryController->softDelete($_POST['category_id']);
});

// Dashboard Routes
$router->add('dashboard/stats', function () use ($dashboardController) {
    Authenticate::checkAuth();
    echo $dashboardController->stats($_SESSION['user_id']);
});

// View Routes

$router->add('', function () {
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        header('Location: /dashboard');
        exit;
    } else {
        header('Location: /login');
        exit;
    }
});

$router->add('login', function () use ($authController) {
    Authenticate::checkGuest();
    $authController->loginForm();
});

$router->add('register', function () use ($authController) {
    Authenticate::checkGuest();
    $authController->registerForm();
});

$router->add('dashboard', function () {
    Authenticate::checkAuth();
    include './public/views/dashboard.php';
});

$router->add('tasks', function () {
    Authenticate::checkAuth();
    include './public/views/tasks.php';
});

$router->add('add-task', function () {
    Authenticate::checkAuth();
    $categoryId = $_GET['category_id'] ?? null;
    $userId = $_GET['user_id'] ?? null;

    if (!$categoryId || !$userId) {
        echo "Category ID or User ID is missing!";
        return;
    }

    include './public/views/add-task.php';
});

$router->add('edit-task', function () use ($taskController) {
    Authenticate::checkAuth();

    $taskId = $_GET['task_id'] ?? null;
    $categoryId = $_GET['category_id'] ?? null;
    $userId = $_GET['user_id'] ?? null;

    if (!$taskId || !$userId || !$categoryId) {
        echo "Task ID, User ID, or Category ID is missing!";
        return;
    }

    $taskJson = $taskController->getById($taskId);
    $taskData = json_decode($taskJson, true);

    if ($taskData['status'] !== 'success') {
        echo $taskData['message'];
        return;
    }

    $task = $taskData['data'];
    include './public/views/edit-task.php';
});

$router->add('profile', function () {
    Authenticate::checkAuth();
    include './public/views/profile.php';
});

$router->add('api/profile', function () use ($profileController) {
    Authenticate::checkAuth();
    $userId = $_SESSION['user_id'];
    echo $profileController->show($userId);
});


$uri = $_SERVER['REQUEST_URI'];
$router->dispatch($uri);
