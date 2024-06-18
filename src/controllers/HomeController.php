<?php

    namespace Horizon\Src\Controllers;

    use Horizon\Core\CoreController;
    use Horizon\Core\Entities\Migrations;
    use Horizon\Core\Mystic\Mystic;
    use Horizon\Src\Views\Forms\LoginForm;

    class HomeController extends CoreController{
        public function index() {
            $loginForm = $this->renderForm(LoginForm::class);
            $migrationInstance = Mystic::fetchAll(Migrations::class);
            $this->render("Home", ['loginForm' => $loginForm, 'migration' => $migrationInstance]);
        }
        
        public function api() {
            echo "Welcome to api";
        }
    }