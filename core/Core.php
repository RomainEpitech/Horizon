<?php

    namespace Horizon\Core;

    use Horizon\Core\Env\EnvLoader;
    use Horizon\Core\Router\Router;
    class Core {

        public function run() {
            require_once './routes/Web.php';
            EnvLoader::load(__DIR__ . '/../.env');

            $routing = EnvLoader::get('ROUTING_TYPE');
            $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $method = $_SERVER['REQUEST_METHOD'];

            // Router::dispatch($method, $url);
        }
    }