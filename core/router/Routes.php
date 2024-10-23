<?php

namespace Horizon\Core\Router;

class Routes {
    private static $routes = [
        'WEB' => [
            'GET' => [],
            'POST' => [],
            'PUT' => [],
            'DELETE' => []
        ],
        'API' => [
            'GET' => [],
            'POST' => [],
            'PUT' => [],
            'DELETE' => []
        ]
    ];

    private static $routerType;

    public static function init(): void {
        self::$routerType = strtoupper($_ENV['ROUTER_TYPE']);
        if (self::$routerType === 'API') {
            require_once './routes/api/api.php';
        } else {
            require_once './routes/web.php';
        }
    }

    private static function connect($method, $url, $controllerAction) {
        self::$routes[self::$routerType][strtoupper($method)][$url] = [
            'controllerAction' => $controllerAction
        ];
    }

    public static function get($url, $controllerAction) {
        self::connect('GET', $url, $controllerAction);
    }

    public static function post($url, $controllerAction) {
        self::connect('POST', $url, $controllerAction);
    }

    public static function put($url, $controllerAction) {
        self::connect('PUT', $url, $controllerAction);
    }

    public static function delete($url, $controllerAction) {
        self::connect('DELETE', $url, $controllerAction);
    }

    public static function dispatch($method, $url) {
        $method = strtoupper($method);
        
        if (!isset(self::$routes[self::$routerType][$method])) {
            self::handleNotFound();
            return null;
        }

        foreach (self::$routes[self::$routerType][$method] as $route => $details) {
            $pattern = preg_replace('/\{[^\}]+\}/', '([^/]+)', $route);
            
            if (preg_match("#^$pattern$#", $url, $matches)) {
                array_shift($matches);
                $controllerAction = $details['controllerAction'];
                
                foreach ($controllerAction as $controller => $action) {
                    $controllerInstance = new $controller();
                    
                    if (self::$routerType === 'API') {
                        return self::handleApiResponse(
                            call_user_func_array([$controllerInstance, $action], $matches)
                        );
                    }

                    return call_user_func_array([$controllerInstance, $action], $matches);
                }
            }
        }

        self::handleNotFound();
        return null;
    }

    private static function handleApiResponse($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        return null;
    }

    private static function handleNotFound() {
        if (self::$routerType === 'API') {
            self::handleApiResponse(['error' => 'Route not found'], 404);
        } else {
            require_once __DIR__ . '/../views/errors/404.php';
        }
    }
}