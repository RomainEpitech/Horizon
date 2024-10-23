<?php

    namespace Horizon\Core\Commands;

    use Horizon\Core\Commands\Tools\HorizonServer;

    require './vendor/autoload.php';

    class CommandHandler {
        public function handle($command, $params) {
            switch ($command) {
                case 'serv:run':
                    $runServ = new HorizonServer;
                    $runServ->startServer($params);
                    break;
            }
        }
    }