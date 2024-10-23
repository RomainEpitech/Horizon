<?php

    namespace Horizon\Src\Controllers;

use Horizon\Core\CoreController;

    class HomeController extends CoreController {
        public function renderHome() {
            $this->render("Home");
        }
    }