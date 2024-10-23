<?php

    namespace Horizon\Core;

    use Horizon\Core\Database\Database;
    use Horizon\Core\Env\EnvLoader;
    use Horizon\Core\Router\Routes;

    class Core {
        public function run() {
            $loader = new EnvLoader();
            $loader->load();

            $dbConnect = isset($_ENV['DB_CONNECT']) && 
                (($_ENV['DB_CONNECT'] === true) ||
                (is_string($_ENV['DB_CONNECT']) && 
                strtolower($_ENV['DB_CONNECT']) === 'true'));

            if ($dbConnect) {
                Database::run();
            }

            Routes::init();
            Routes::dispatch(
                $_SERVER['REQUEST_METHOD'],
                parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
            );
        }
    }