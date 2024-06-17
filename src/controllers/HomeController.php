<?php

    namespace Horizon\Src\Controllers;

    use Horizon\Core\CoreController;

    class HomeController extends CoreController{
        public function index() {
            $this->render("Home");
        }
        public function api() {
            echo "Welcome to api";
        }
    }