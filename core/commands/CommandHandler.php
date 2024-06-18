<?php

    namespace Horizon\Core\Commands;
    require './vendor/autoload.php';

    use Horizon\Core\Commands\Tools\HorizonController;
    use Horizon\Core\Commands\Tools\HorizonEntity;
    use Horizon\Core\Commands\Tools\HorizonFormBuilder;
    use Horizon\Core\Commands\Tools\HorizonMigration;
    use Horizon\Core\Commands\Tools\HorizonServer;
    use Horizon\Core\LogHandler;
    class CommandHandler {
        public function handle($command, $params) {
            switch ($command) {
                case 'serv:run':
                    $runServ = new HorizonServer;
                    $runServ->startServer($params);
                    break;
                case 'migration:new':
                    $newMigration = new HorizonMigration();
                    $newMigration->newMigration();
                    break;
                case 'migration:run':
                    $runMigration = new HorizonMigration();
                    $runMigration->makeMigration();
                    break;
                case 'build:run':
                    $this->runBuildScript();
                    break;
                case 'reset:run':
                    $this->runResetScript();
                    break;
                case 'log:clear':
                    $logHandler = new LogHandler();
                    $logHandler->clearLog();
                    $this->displaySuccessMessage("Log cleared successfully");
                    break;
                case 'entities:make': 
                    $outputDir = __DIR__ . '/../../core/entities';
                    $entities = new HorizonEntity;
                    $entities->generateEntities($outputDir);
                    break;
                case 'controller:new':
                    if(!$params) {
                        die($this->displayErrorMessage("Please enter a valid controller name"));
                    }
                    $newController = new HorizonController();
                    $newController->makeController($params);
                    $this->displaySuccessMessage("$params[0]Controller created successfully");
                    break;
                case 'form:create':
                    if ($params) {
                        $formName = $params[0];
                        $formBuilder = new HorizonFormBuilder();
                        $formBuilder->createForm($formName);
                    } else {
                        echo "Usage: ./Horizon form:make <FormName>\n";;
                    }
                    break;
            }
        }

        private function runBuildScript() {
            $scriptPath = __DIR__ . '/../../settings/build.sh';
            
            if (!is_executable($scriptPath)) {
                echo "Build script is not executable. Attempting to set executable permissions...\n";
                chmod($scriptPath, 0755);
            }
    
            if (is_executable($scriptPath)) {
                echo "Executing build script...\n";
                passthru($scriptPath, $returnVar);
                if ($returnVar !== 0) {
                    echo "Build script failed with status code: $returnVar\n";
                } else {
                    echo "Build script executed successfully.\n";
                }
            }
        }

        private function runResetScript() {
            $scriptPath = __DIR__ . '/../../settings/bin/reset.sh';

            if (!is_executable($scriptPath)) {
                echo "Build script is not executable. Attempting to set executable permissions...\n";
                chmod($scriptPath, 0755);
            }

            if (is_executable($scriptPath)) {
                echo "Executing build script...\n";
                passthru($scriptPath, $returnVar);
                if ($returnVar !== 0) {
                    echo "Build script failed with status code: $returnVar\n";
                } else {
                    echo "Build script executed successfully.\n";
                }
            }
        }

        protected function displaySuccessMessage($message) {
            $greenBackground = "\033[42m";
            $blackText = "\033[30m";
            $reset = "\033[0m";

            echo "{$greenBackground}{$blackText}\n";
            echo str_pad(" ", 80) . "\n";
            echo str_pad(" $message ", 80, " ", STR_PAD_BOTH) . "\n";
            echo str_pad(" ", 80) . "\n";
            echo $reset;
        }

        protected function displayErrorMessage($message) {
            $redBackground = "\033[41m";
            $whiteText = "\033[97m";
            $reset = "\033[0m";

            echo "{$redBackground}{$whiteText}\n";
            echo str_pad(" ", 80) . "\n";
            echo str_pad(" $message ", 80, " ", STR_PAD_BOTH) . "\n";
            echo str_pad(" ", 80) . "\n";
            echo $reset;
        }
    }