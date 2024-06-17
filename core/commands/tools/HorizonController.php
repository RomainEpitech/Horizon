<?php

    namespace Horizon\Core\Commands\Tools;

    use Horizon\Core\LogHandler;

    class HorizonController {

        public function makeController($params) {
            $name = $params[0] ?? null;
            if ($name) {
                $name .= 'Controller';
                $filePath = __DIR__ . "/../../../src/Controllers/{$name}.php";
                $controllerTemplate = "<?php\n\n    namespace Horizon\Src\Controllers; \n    use Horizon\Core\CoreController; \n\n    class {$name} extends CoreController {\n        public function index() {\n        //\n        }\n}\n";

                if (!file_exists(dirname($filePath))) {
                    mkdir(dirname($filePath), 0777, true);
                }
                
                file_put_contents("./src/Controllers/{$name}.php", $controllerTemplate);
                $log = new LogHandler();
                $log->log("[Controller] New $name controller created.");
            } else {
                $log = new LogHandler();
                $log->log("[Controller] Failed to create $name controller.");
            }
        }

    }