<?php

namespace Horizon\Core\Commands\Tools;

class HorizonServer {
    public function startServer($params) {
        $host = $params[0] ?? '127.0.0.1';
        $port = $params[1] ?? '8888';
        $docRoot = __DIR__ . '/../../../';

        $timeStart = microtime(true);

        $progressBarLength = exec('tput cols');
        if (!$progressBarLength) {
            $progressBarLength = 80;
        }
        $progressBarLength -= 20;

        $timeStart = microtime(true);

        $colorGreen = "\033[32m";
        $colorReset = "\033[0m";

        echo "\033[2J\033[H";

        $horizon = "
\033[31m _   _    ___    ____    ___    ____    ___    _   _
\033[33m| | | |  / _ \\  |  _ \\  |_ _|  |__  |  / _ \\  | \\ | |
\033[32m| |_| | | | | | | (_) |  | |     / /  | | | | |  \\| |
\033[36m|  _  | | | | | |  _ /   | |    / /   | | | | | .   |
\033[34m| | | | | |_| | | | \\ \\  | |   / /_   | |_| | | |\\  |
\033[35m|_| |_|  \\___/  |_|  \\_\\|___| |____|   \\___/  |_| \\_|
\033[0m
";
        echo $horizon;

        $serverUrl = "$colorGreen" . "┌" . str_repeat("─", strlen(" Starting server at http://$host:$port ")) . "┐\n";
        $serverUrl .= "│ Starting server at http://$host:$port │\n";
        $serverUrl .= "└" . str_repeat("─", strlen(" Starting server at http://$host:$port ")) . "┘$colorReset\n";

        echo $serverUrl;

        passthru("php -S $host:$port -t $docRoot");
    }
}