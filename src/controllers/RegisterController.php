<?php

    namespace Horizon\Src\Controllers;

    use Horizon\Core\CoreController;
    use Horizon\Core\Entities\Users;
    use Horizon\Core\Guards\Http\Request;
    use Horizon\Core\Mystic\Mystic;

    class RegisterController extends CoreController {
        public function index(Request $request) {
            if ($request->method('POST')) {
                $email = $request->validateEmail('email');
                $password = $request->sanitizeString('password');
                echo "password: " . $password . "email : " . $email;
                $newUser = new Mystic();
                $newUser->insert(Users::class, [
                    "email" => $email,
                    "password" => $password
                ]);

                if ($newUser) {
                    echo "new user";
                } else {
                    die("failed");
                }
            }
        }
    }