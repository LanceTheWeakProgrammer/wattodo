<?php 

class Router {
    private $routes = [];

    public function add ($path, $callback) {
        $this->routes[$path] = $callback;
    }

    public function dispatch($uri) {
        $uri = trim(parse_url($uri, PHP_URL_PATH), '/');
        if (array_key_exists($uri, $this->routes)) {
            call_user_func($this->routes[$uri]);
        } else {
            include './public/views/notfound.php';
        }
    }
}