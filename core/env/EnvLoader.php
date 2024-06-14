<?php

    namespace Horizon\Core\Env;

    class EnvLoader {

        private static $vars = [];

        public static function load($filePath) {

            if (!file_exists($filePath)) {
                throw new \Exception("Env file not found: " . $filePath);
            }

            $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }

                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);

                self::$vars[$name] = $value;
            }
        }

        public static function get($key, $default = null) {
            return self::$vars[$key] ?? $default;
        }
    }