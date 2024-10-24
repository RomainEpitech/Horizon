<?php

    namespace Horizon\Core\Commands\Migrations;

    use PDO;

    class Schema {
        protected static $pdo;

        public static function setPdo(PDO $pdo) {
            self::$pdo = $pdo;
        }

        public static function newTable(string $tableName, \Closure $callback): void {
            $blueprint = new Blueprint($tableName);
            $callback($blueprint);
            $blueprint->build(self::$pdo);
        }

        public static function table(string $tableName, \Closure $callback): void {
            $blueprint = new Blueprint($tableName, true);
            $callback($blueprint);
            $blueprint->build(self::$pdo);
        }

        public static function dropTable(string $tableName): void {
            self::$pdo->exec("DROP TABLE IF EXISTS `$tableName`");
        }

        public static function dropIfExists(string $tableName): void {
            self::dropTable($tableName);
        }

        public static function rename(string $from, string $to): void {
            self::$pdo->exec("RENAME TABLE `$from` TO `$to`");
        }
    }