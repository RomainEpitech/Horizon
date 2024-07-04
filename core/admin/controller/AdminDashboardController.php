<?php

    namespace Horizon\Core\Admin\Controller;

    use Horizon\Core\CoreController;

    class AdminDashboardController extends CoreController {

        public function index() {
            $this->renderAdmin("Dashboard");
        }
    }