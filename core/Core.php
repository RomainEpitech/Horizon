<?php

    namespace Horizon\Core;

    use Horizon\Core\Database\Database;
    use Horizon\Core\Env\EnvLoader;

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
                echo "connected";
            }

            echo "it is running " . $_ENV['APP_NAME'];
        }
    }