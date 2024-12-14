<?php 

class Authenticate {
    public static function checkGuest() {
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
            header('Location: /dashboard');
            exit();
        }
    }

    public static function checkAuth() {
        if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
            header('Location: /login');
            exit();
        }
    }
}