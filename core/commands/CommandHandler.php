<?php

    namespace Horizon\Core\Commands;
    require './vendor/autoload.php';

    use Horizon\Core\Commands\Tools\HorizonMigration;
    use Horizon\Core\Commands\Tools\HorizonServer;

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
            }
        }

        protected function displaySuccessMessage($message) {
            $greenBackground = "\033[42m";
            $whiteText = "\033[97m";

            echo "{$greenBackground}{$whiteText}\n";
            echo str_pad(" ", 80) . "\n";
            echo str_pad(" $message ", 80, " ", STR_PAD_BOTH) . "\n";
            echo str_pad(" ", 80) . "\n";
        }

        protected function displayErrorMessage($message) {
            $redBackground = "\033[41m";
            $whiteText = "\033[97m";

            echo "{$redBackground}{$whiteText}\n";
            echo str_pad(" ", 80) . "\n";
            echo str_pad(" $message ", 80, " ", STR_PAD_BOTH) . "\n";
            echo str_pad(" ", 80) . "\n";
        }
    }