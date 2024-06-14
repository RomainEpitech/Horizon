<?php

    namespace Horizon\Core\Router;

    class ApiRouter extends Router {
        public static function connect($method, $url, $controllerAction) {
            parent::connect($method, '/api' . $url, $controllerAction);
        }

        public static function get($url, $controllerAction) {
            self::connect('GET', $url, $controllerAction);
        }

        public static function post($url, $controllerAction) {
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
    }
