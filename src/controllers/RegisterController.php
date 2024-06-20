<?php

    namespace Horizon\Src\Controllers;

    use Horizon\Core\CoreController;
    use Horizon\Core\Guards\Auth\Auth;
    use Horizon\Core\Guards\Http\Request;

    class RegisterController extends CoreController {
        public function index(Request $request) {
            if ($request->method('POST')) {
                $params = Request::getFields();

                $newUser = Auth::registerUser($params);

                if ($newUser) {
                    print_r($params);
                    echo "new user";
                } else {
                    die("failed");
                }
            }
        }
    }