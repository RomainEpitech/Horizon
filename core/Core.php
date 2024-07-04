<?php

    namespace Horizon\Core;

    use Horizon\Core\Database\Database;
    use Horizon\Core\Env\EnvLoader;
    use Horizon\Core\Router\AdminRouter;
    use Horizon\Core\Router\ApiRouter;
    use Horizon\Core\Router\Router;
    class Core {

        public function run() {

            $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $method = $_SERVER['REQUEST_METHOD'];
            EnvLoader::load('./.env');

            $routingType = EnvLoader::get('ROUTING_TYPE');

            if (strpos($url, '/admin') === 0) {
                require_once './Core/Admin/Routes/AdminRoutes.php';
                AdminRouter::dispatch($method, $url);
            } else {
                $routingType = EnvLoader::get('ROUTING_TYPE');

                if ($routingType === 'web') {
                    require_once './routes/Web.php';
                    Router::dispatch($method, $url);
                } elseif ($routingType === 'api') {
                    require_once './routes/api/Api.php';
                    ApiRouter::dispatch($method, $url);
                } else {
                    die(".env ROUTING_TYPE value is wrong\n");
                }
            }
            $DbConnect = EnvLoader::get('DB_ACCESS');

            if($DbConnect === 'true') $Database = new Database;
        }
    }