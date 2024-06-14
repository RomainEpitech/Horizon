<?php

    namespace Horizon\Core;

    use Horizon\Core\Database\Database;
    use Horizon\Core\Env\EnvLoader;
    use Horizon\Core\Router\Router;
    class Core {

        public function run() {
            require_once './routes/Web.php';

            $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $method = $_SERVER['REQUEST_METHOD'];

            Router::dispatch($method, $url);

            EnvLoader::load('./.env');
            $DbConnect = EnvLoader::get('DB_ACCESS');

            if($DbConnect === 'true') $Database = new Database;
            else echo "ok";
        }
    }