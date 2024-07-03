<?php

    namespace Horizon\Core\Router;

    use Horizon\Core\Guards\Http\Request;

    class Router {
        private static $routes = [];

        public static function connect($method, $url, $controllerAction, $middleware = []) {
            self::$routes[strtoupper($method)][$url] = [
                'controllerAction' => $controllerAction,
                'middleware' => $middleware
            ];
        }

        public static function get($url, $controllerAction, $middleware = []) {
            self::connect('GET', $url, $controllerAction, $middleware);
        }

        public static function post($url, $controllerAction, $middleware = []) {
            self::connect('POST', $url, $controllerAction, $middleware);
        }

        public static function put($url, $controllerAction, $middleware = []) {
            self::connect('PUT', $url, $controllerAction, $middleware);
        }

        public static function delete($url, $controllerAction, $middleware = []) {
            self::connect('DELETE', $url, $controllerAction, $middleware);
        }

        public static function dispatch($method, $url) {
            $method = strtoupper($method);
            foreach (self::$routes[$method] as $route => $controllerAction) {
                $pattern = preg_replace('/\{[^\}]+\}/', '([^/]+)', $route);
                if (preg_match("#^$pattern$#", $url, $matches)) {
                    array_shift($matches);

                    $routeInfo = self::$routes[$method][$route];
                    foreach ($routeInfo['middleware'] as $middleware) {
                        $middlewareClass = $middleware[0];
                        $middlewareParams = $middleware[1] ?? [];
                        if (class_exists($middlewareClass) && method_exists($middlewareClass, 'handle')) {
                            $middlewareInstance = new $middlewareClass();
                            if (!$middlewareInstance::handle(new Request(), ...$middlewareParams)) {
                                return null;
                            }
                        } else {
                            echo "Middleware class or handle method does not exist: $middlewareClass\n";
                        }
                    }

                    $controllerAction = $routeInfo['controllerAction'];
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
