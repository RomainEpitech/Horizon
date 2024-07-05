<?php

    namespace Horizon\Core\Admin\Controller;

    use Horizon\Core\CoreController;

    class AdminDashboardController extends CoreController {

        public function index() {
            $user = $_SESSION['current_User'];
            $this->renderAdmin("Dashboard", ['user' => $user]);
        }
    }