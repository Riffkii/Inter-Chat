<?php

namespace Web\InterChat\Util;

class Router {

    private static array $routes = [];

    public static function add(string $method, string $path, string $controller, string $function, array $middleware = []) {
        self::$routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'function' => $function,
            'middleware' => $middleware
        ];
    }

    public static function run() {
        $path = '/';
        if(isset($_SERVER['PATH_INFO'])) $path = $_SERVER['PATH_INFO'];
        $method = $_SERVER['REQUEST_METHOD'];

        foreach(self::$routes as $route) {
            if($route['method'] == $method && $route['path'] == $path) {
                foreach($route['middleware'] as $middleware) {
                    $m = new $middleware;
                    $m->before();
                }
                
                $controller = new $route['controller'];
                $function = $route['function'];
                $controller->$function();
            }
        }
    }
}