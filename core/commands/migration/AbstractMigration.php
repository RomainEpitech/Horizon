<?php

    namespace Horizon\Core\Commands\Migration;

    use PDO;
    use PDOException;
    use Exception;
    use Horizon\Core\Database\Database;

    class AbstractMigration {
        protected $pdo;

        public function __construct(PDO $pdo) {
            $database = Database::getInstance();
            $this->pdo = $database->getConn();
        }

        protected function executeSql(string $sql) {
            try {
                $this->pdo->exec($sql);
            } catch (PDOException $e) {
                throw new Exception("SQL execution error: " . $e->getMessage());
            }
        }
    }