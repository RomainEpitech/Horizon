<?php

    namespace Horizon\Core\Commands\Tools;

    class HorizonServer {
        public function startServer($params) {
            $host = $params[0] ?? '127.0.0.1';
            $port = $params[1] ?? '8888';
            $docRoot = __DIR__ . '/../../../';
            
            echo "Starting server at \e[96mhttp://$host:$port\n\e[0m";
            passthru("php -S $host:$port -t $docRoot");
        }
    }