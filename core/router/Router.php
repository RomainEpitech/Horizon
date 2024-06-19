<?php

    namespace Horizon\Core\Router;

    use Horizon\Core\Guards\Http\Request;

    class Router {
        private static $routes = [];
    
        public static function connect($method, $url, $controllerAction)
        {
            self::$routes[strtoupper($method)][$url] = $controllerAction;
        }
    
        public static function get($url, $controllerAction)
        {
            self::connect('GET', $url, $controllerAction);
        }
    
        public static function post($url, $controllerAction)
        {
            self::connect('POST', $url, $controllerAction);
        }
    
        public static function put($url, $controllerAction)
        {
            self::connect('PUT', $url, $controllerAction);
        }
    
        public static function delete($url, $controllerAction)
        {
            self::connect('DELETE', $url, $controllerAction);
        }
    
        public static function dispatch($method, $url) {
            $method = strtoupper($method);
            foreach (self::$routes[$method] as $route => $controllerAction) {
                $pattern = preg_replace('/\{[^\}]+\}/', '([^/]+)', $route);
                if (preg_match("#^$pattern$#", $url, $matches)) {
                    array_shift($matches);
    
                    foreach ($controllerAction as $controller => $action) {
                        $controllerInstance = new $controller();
                        $request = new Request();
                        return call_user_func_array([$controllerInstance, $action], [$request]);
                    }
                }
            }
    
            require_once __DIR__ . '/../src/views/errors/404.php';
            return null;
        }
    }