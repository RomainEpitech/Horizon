<?php

    namespace Horizon\Core\Commands\Migrations;

    use Horizon\Core\Database\Database;
    use PDO;

    abstract class AbstractMigration {
        protected $pdo;

        public function __construct(PDO $pdo) {
            $database = Database::run();
        }
    }