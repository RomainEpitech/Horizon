<?php

    namespace Horizon\Src\Controllers;

    use Horizon\Core\CoreController;
    use Horizon\Src\Views\Forms\LoginForm;

    class HomeController extends CoreController{
        public function index() {
            $loginForm = $this->renderForm(LoginForm::class);
            $this->render("Home", ['loginForm' => $loginForm]);
        }
        public function api() {
            echo "Welcome to api";
        }
    }