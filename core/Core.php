<?php

    namespace Horizon\Core;

    use Horizon\Core\Router\Router;

    class Core {

        public function run() {
            require_once './routes/Web.php';

            $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $method = $_SERVER['REQUEST_METHOD'];

            Router::dispatch($method, $url);
        }
    }