<?php

    namespace Horizon\Core;
    use Horizon\Core\Env\EnvLoader; 

    class LogHandler {
        private $logFile;
        private $date;

        public function __construct($logFile = __DIR__ . '/../logs/Log.txt', $date = null) {
            EnvLoader::load('./.env');
            $timezone = EnvLoader::get('TIMEZONE');
            date_default_timezone_set($timezone);
            $this->logFile = $logFile;
            $logDir = dirname($this->logFile);
            if (!is_dir($logDir)) {
                mkdir($logDir, 0777, true);
            }
            $this->date = $date ?? date('Y-m-d H:i:s');
        }

        public function log($message) {
            $logMessage = "[$this->date] $message" . PHP_EOL;
            file_put_contents($this->logFile, $logMessage, FILE_APPEND);
        }

        public function clearLog() {
            file_put_contents($this->logFile, '');
            echo "Log file cleared\n";
        }
    }