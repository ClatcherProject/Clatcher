<?php
class Router {
    private $routes = [];

    public function add($method, $pattern, $callback) {
        $this->routes[] = compact("method", "pattern", "callback");
    }

    public function dispatch($method, $uri) {
        foreach($this->routes as $route) {
            if($method == $route["method"] && preg_match($route["pattern"], $uri, $matches)) {
                return call_user_func_array($route["callback"], array_slice($matches, 1));
            }
        }
    }
}
?>