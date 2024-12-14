<?php 

require_once './app/models/User.php';
require_once './app/validators/UserValidator.php';
require_once 'Controller.php';

class AuthController extends Controller {
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
    }

    public function loginForm() {
        include './public/views/login.php';
    }

    public function registerForm() {
        include './public/views/register.php';
    }

    public function store($username, $name, $password) {
        try {
            $errors = UserValidator::validate([
                'username' => $username,
                'name' => $name,
                'password' => $password,
            ]);
    
            if (!empty($errors)) {
                return json_encode([
                    'status' => 'error',
                    'message' => $errors,
                ]);
            }
    
            if ($this->user->store($username, $name, $password)) {
                return json_encode([
                    'status' => 'success',
                    'message' => 'User registered successfully',
                ]);
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Failed to register user.',
                ]);
            }
        } catch (Exception $e) {
            return json_encode([
                'status' => 'error',
                'message' => 'Server error.',
            ]);
        }
    }

    public function authenticate($username, $password) {
        try {
            $user = $this->user->auth($username); 

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $user['id']; 

                return json_encode([
                    'status' => 'success',
                    'message' => 'Logged in successfully!'
                ]);
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Invalid username or password'
                ]);
            }
        } catch (\Throwable $th) {
            return json_encode([
                'status' => 'error',
                'message' => 'Server error'                
            ]);
        }
    }

    public function destroy() {
        try {
            session_unset();

            session_destroy();

            if(ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();

                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }

            return json_encode([
                'status' => 'success',
                'message' => 'Logout successfully'
            ]);
        } catch (Exception $e) {
            return json_encode([
                'status' => 'error',
                'message' => 'Logout failed'
            ]);
        }
    }
}