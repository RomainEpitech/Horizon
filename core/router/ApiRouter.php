<?php

    namespace Horizon\Core\Router;

    class ApiRouter extends Router {
        public static function connect($method, $url, $controllerAction, $middleware = []) {
            parent::connect($method, '/api' . $url, $controllerAction, $middleware);
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
    }
