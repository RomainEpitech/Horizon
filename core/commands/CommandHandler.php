<?php

    namespace Horizon\Core\Commands;

    use Horizon\Core\Commands\Tools\HorizonServer;

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