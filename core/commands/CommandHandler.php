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
                case 'migration:run':
                    $runMigration = new HorizonMigration();
                    $runMigration->makeMigration();
                    break;
            }
        }
    }