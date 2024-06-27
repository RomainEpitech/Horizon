<?php

    namespace Horizon\Src\Controllers;

    use Exception;
    use Horizon\Core\CoreController;
    use Horizon\Core\Entities\Migrations;
    use Horizon\Core\Guards\Auth\Auth;
    use Horizon\Core\Guards\Http\Request;
    use Horizon\Core\Mystic\Mystic;
    use Horizon\Src\Views\Forms\AccessForm;
    use Horizon\Src\Views\Forms\LoginForm;

    class HomeController extends CoreController{
        public function index() {
            $loginForm = $this->renderForm(LoginForm::class);
            $accessForm = $this->renderForm(AccessForm::class);
            $migrationInstance = Mystic::fetchAll(Migrations::class);
            $this->render("Home", ['loginForm' => $loginForm, 'migration' => $migrationInstance, 'accessForm' => $accessForm]);
        }

        public function login(Request $request) {
            if ($request->isMethod('POST')) {
                $params = $request->getFields();
    
                try {
                    $loginResponse = Auth::loginUser($params);
                    echo "Login successful. Token: " . $loginResponse['token'];
                } catch (Exception $e) {
                    // Handle login failure
                    echo $e->getMessage();
                }
            } else {
                // Display the login form
                $loginForm = $this->renderForm(AccessForm::class);
                $this->render("Login", ['loginForm' => $loginForm]);
            }
        }
        
        public function api() {
            echo "Welcome to api";
        }
    }