<?php

    namespace Horizon\Core\Commands;
    require './vendor/autoload.php';

    use Horizon\Core\Commands\Tools\HorizonMigration;
    use Horizon\Core\Commands\Tools\HorizonServer;
use Horizon\Core\Inc\Error;
use Horizon\Core\Inc\success;
    use Horizon\Core\Logs\Log;

    class CommandHandler {
        public function handle($command, $params) {
            switch ($command) {
                case 'serv:run':
                    $runServ = new HorizonServer;
                    $runServ->startServer($params);
                    break;
                
                // MIGRATIONS
                case 'migration:new':
                    $newMigration = new HorizonMigration();
                    $migrationName = $params[0] ?? null;
                    $newMigration->newMigration($migrationName);
                    break;
                case 'migration:run':
                    $runMigration = new HorizonMigration();
                    $runMigration->makeMigration();
                    break;

                // LOGGING
                case 'logs:clear':
                    Log::clear();
                    success::displaySuccessMessage("Log file cleared.");
                    break;
                case 'logs:filter':
                    if (empty($params[0])) {
                        Error::displayErrorMessage("No log level specified.");
                        exit(1);
                    }
                    $logLevel = strtoupper($params[0]);
                    Log::orderByStatus($logLevel);
                    break;
            }
        }
    }