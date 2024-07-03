<?php

    namespace Horizon\Core\Commands\Tools;

    class HorizonServer {
        public function startServer($params) {
            $host = $params[0] ?? '127.0.0.1';
            $port = $params[1] ?? '8888';
            $docRoot = __DIR__ . '/../../../';

            $progressBarLength = exec('tput cols');
            if (!$progressBarLength) {
                $progressBarLength = 80;
            }
            $progressBarLength -= 20;

            $timeStart = microtime(true);

            $descriptorspec = [
                0 => ["pipe", "r"],
                1 => ["pipe", "w"],
                2 => ["pipe", "w"],
            ];

            $process = proc_open('npm run build:css', $descriptorspec, $pipes, null, null, ['bypass_shell' => true]);

            if (is_resource($process)) {
                fclose($pipes[0]);

                $output = '';
                $totalLines = 0;
                $progress = 0;

                while (!feof($pipes[1])) {
                    $line = fgets($pipes[1]);
                    if ($line !== false) {
                        $output .= $line;
                        $totalLines++;
                        $progress = min($progressBarLength, (int)($totalLines / 2));
                        $progressBar = str_repeat("#", $progress) . str_repeat(" ", $progressBarLength - $progress);
                        $timeElapsed = round(microtime(true) - $timeStart, 1);
                        echo "\rTailwind: $progressBar $timeElapsed" . "s";
                        flush();
                    }
                }

                fclose($pipes[1]);
                fclose($pipes[2]);

                $return_value = proc_close($process);

                $timeEnd = microtime(true);
                $timeElapsed = round($timeEnd - $timeStart, 1);

                $colorGreen = "\033[32m";
                $colorRed = "\033[31m";
                $colorReset = "\033[0m";

                if ($return_value !== 0) {
                    echo "\n" . $colorRed . "Tailwind CSS compilation failed:" . $colorReset . "\n";
                    echo $output;
                    return;
                }

                echo "\r" . $colorGreen . "Tailwind: " . str_repeat("#", $progressBarLength) . " $timeElapsed" . "s" . $colorReset . "\n";
                echo "Starting server at http://$host:$port\n";
                passthru("php -S $host:$port -t $docRoot");
            } else {
                echo "Failed to start the Tailwind CSS compilation process.";
            }
        }
    }
