<?php

    namespace Horizon\Core\Admin\Controller;

    use Horizon\Core\CoreController;
    use Horizon\Core\Env\EnvLoader;

    class AdminDashboardController extends CoreController {

        public function index() {

            $appName = EnvLoader::get('APP_NAME');
            $this->renderAdmin("Dashboard", ['user' => $_SESSION['current_User'], 'AppName' => $appName]);
        }
    }