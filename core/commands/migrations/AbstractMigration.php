<?php

    namespace Horizon\Core\Commands\Migrations;

    use Horizon\Core\Database\Database;
    use PDO;

    abstract class AbstractMigration {
        protected PDO $pdo;

        public function __construct(PDO $pdo) {
            $database = Database::run();
            $this->pdo = $database->getConn();
            Schema::setPdo($this->pdo);
        }
    }