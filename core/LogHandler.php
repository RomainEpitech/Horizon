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

        public function newMigration($migrationFile) {
            $logMessage = "[$this->date][Migration] New version$migrationFile created." . PHP_EOL;
            file_put_contents($this->logFile, $logMessage, FILE_APPEND);
        }

        public function makeMigration($migrationFile) {
            $logMessage = "[$this->date][Migration] $migrationFile executed." . PHP_EOL;
            file_put_contents($this->logFile, $logMessage, FILE_APPEND);
        }

        public function failedMigration($migration) {
            $logMessage = "[$this->date][Migration] $migration already exist in database." . PHP_EOL;
            file_put_contents($this->logFile, $logMessage, FILE_APPEND);
        }

        public function entitiesLog() {
            $message = "[$this->date][Entities] Entities created and saved in core/entities" . PHP_EOL;
            file_put_contents($this->logFile, $message, FILE_APPEND);
        }

        public function newForm($formName) {
            $message = "[$this->date][Form] $formName Form created successfully" . PHP_EOL;
            file_put_contents($this->logFile, $message, FILE_APPEND);
        }

        public function newUser($user) {
            $message = "[$this->date][NewUser] $user registered successfully." . PHP_EOL;
            file_put_contents($this->logFile, $message, FILE_APPEND);
        }

        public function failedNewUser($user) {
            $message = "[$this->date][NewUser] $user failed to be registered." . PHP_EOL;
            file_put_contents($this->logFile, $message, FILE_APPEND);
        }

        public function log($message) {
            $logMessage = "[$this->date] $message" . PHP_EOL;
            file_put_contents($this->logFile, $logMessage, FILE_APPEND);
        }

        public function clearLog() {
            file_put_contents($this->logFile, '');
        }
    }