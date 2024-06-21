<?php

    namespace Horizon\Core\Commands\Tools;

    class HorizonServer {
        public function startServer($params) {
            $host = $params[0] ?? '127.0.0.1';
            $port = $params[1] ?? '8888';
            $docRoot = __DIR__ . '/../../../';
    
            echo "Compiling Tailwind CSS...\n";
            $output = [];
            $resultCode = 0;
            exec('npm run build:css', $output, $resultCode);
            
            if ($resultCode !== 0) {
                echo "Tailwind CSS compilation failed:\n";
                echo implode("\n", $output);
                return;
            }
    
            echo "Tailwind CSS compiled successfully.\n";
            echo "Starting server at http://$host:$port\n";
            passthru("php -S $host:$port -t $docRoot");
        }
    }