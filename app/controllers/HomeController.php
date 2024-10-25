<?php

    namespace Horizon\App\Controllers;

    use Horizon\Core\CoreController;

    class HomeController extends CoreController {
        public function renderHome() {
            $this->renderMain("Home");
        }
    }