<?php

    namespace Horizon\Core\Logs;

    use Exception;
use Horizon\Core\Inc\Error;

    class Log {
        private const LOG_FILE = '/storage/logs/Horizon.log';

        public static function info(string $message): void {
            self::log('INFO', $message);
        }

        public static function alert(string $message): void {
            self::log('ALERT', $message);
        }

        public static function danger(string $message): void {
            self::log('DANGER', $message);
        }

        public static function success(string $message): void {
            self::log('SUCCESS', $message);
        }

        public static function error(string $message): void {
            self::log('ERROR', $message);
        }

        public static function test(string $message): void {
            self::log('TEST', $message);
        }

        private static function log(string $level, string $message): void {
            try {
                $timestamp = date('Y-m-d H:i:s');
                $logDir = dirname(__DIR__, 2) . '/storage/logs';
                $logFile = $logDir . '/Horizon.log';
        
                if (!is_dir($logDir)) {
                    mkdir($logDir, 0777, true);
                }
        
                if (!file_exists($logFile)) {
                    touch($logFile);
                    chmod($logFile, 0666);
                }
        
                $fileMessage = sprintf(
                    "[%s][%s] %s\n",
                    $timestamp,
                    $level,
                    $message
                );
        
                file_put_contents($logFile, $fileMessage, FILE_APPEND);
        
                self::checkLogRotation();
            } catch (Exception $e) {
                error_log("LogHandler Error: " . $e->getMessage());
            }
        }

        public static function clear(): void {
            $logFile = dirname(__DIR__, 2) . '/storage/logs/Horizon.log';
            if (file_exists($logFile)) {
                file_put_contents($logFile, '');
            }
        }

        public static function getLogs(): array {
            $logFile = dirname(__DIR__, 2) . '/storage/logs/Horizon.log';
            if (!file_exists($logFile)) {
                return [];
            }

            return file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }

        public static function showLogs(): void {
            $logFile = dirname(__DIR__, 2) . '/storage/logs/Horizon.log';
            echo file_get_contents($logFile);
        }        

        private static function checkLogRotation(): void {
            $logFile = dirname(__DIR__, 2) . '/storage/logs/Horizon.log';
            $maxSize = 5 * 1024 * 1024; // 5 MB

            if (file_exists($logFile) && filesize($logFile) > $maxSize) {
                $archive = dirname(__DIR__, 2) . '/storage/logs/Horizon_' . date('Y-m-d_H-i-s') . '.log';
                rename($logFile, $archive);
                touch($logFile);
                chmod($logFile, 0666);
            }
        }

        public static function orderByStatus(string $status): void {
            $logs = self::getLogs();
            $filteredLogs = [];
    
            foreach ($logs as $log) {
                if (strpos($log, "[$status]") !== false) {
                    $filteredLogs[] = $log;
                }
            }
    
            if (empty($filteredLogs)) {
                Error::displayErrorMessage("No log found for level: " . $status);
            } else {
                foreach ($filteredLogs as $log) {
                    echo $log . PHP_EOL;
                }
            }
        }
    }