<?php

    namespace Horizon\Core\Commands\Migration;

    use PDO;
    use PDOException;
    use Exception;
    use Horizon\Core\Commands\Tools\HorizonEntity;
    use Horizon\Core\Database\Database;

    class AbstractMigration {
        protected $pdo;

        public function __construct(PDO $pdo) {
            $database = Database::getInstance();
            $this->pdo = $database->getConn();
        }

        public function createTable($table, $columns = []) {
            $columnsSql = [];
            foreach ($columns as $colName => $colType) {
                $columnsSql[] = "$colName $colType";
            }
            $columnsSqlString = implode(", ", $columnsSql);
            $sql = "CREATE TABLE $table ($columnsSqlString)";
            $this->executeSql($sql);
        }

        public function deleteTable($table) {
            $sql = "DROP TABLE IF EXISTS $table";
            $this->executeSql($sql);
        }

        public function updateTable($table, $operations = []) {
            $operationsSql = [];
            foreach ($operations as $operation => $details) {
                switch ($operation) {
                    case 'add':
                        foreach ($details as $colName => $colType) {
                            $operationsSql[] = "ADD $colName $colType";
                        }
                        break;
                    case 'modify':
                        foreach ($details as $colName => $colType) {
                            $operationsSql[] = "MODIFY $colName $colType";
                        }
                        break;
                    case 'drop':
                        foreach ($details as $colName) {
                            $operationsSql[] = "DROP COLUMN $colName";
                        }
                        break;
                    default:
                        throw new Exception("Unknown operation: $operation");
                }
            }
            $operationsSqlString = implode(", ", $operationsSql);
            $sql = "ALTER TABLE $table $operationsSqlString";
            
            $this->executeSql($sql);
        }

        public function addSql($table, $data = []) {
            $columns = implode(", ", array_keys($data));
            $placeholders = implode(", ", array_fill(0, count($data), '?'));
            $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            
            try {
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(array_values($data));
            } catch (PDOException $e) {
                throw new Exception("SQL execution error: " . $e->getMessage());
            }
        }

        public function removeSql($table, $conditions = []) {
            $conditionClauses = [];
            foreach ($conditions as $column => $value) {
                $conditionClauses[] = "$column = ?";
            }
            $conditionSql = implode(" AND ", $conditionClauses);
            $sql = "DELETE FROM $table WHERE $conditionSql";
            
            try {
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(array_values($conditions));
            } catch (PDOException $e) {
                throw new Exception("SQL execution error: " . $e->getMessage());
            }
        }

        public function updateSql($table, $data = [], $conditions = []) {
            $updateClauses = [];
            foreach ($data as $column => $value) {
                $updateClauses[] = "$column = ?";
            }
            $updateSql = implode(", ", $updateClauses);
    
            $conditionClauses = [];
            foreach ($conditions as $column => $value) {
                $conditionClauses[] = "$column = ?";
            }
            $conditionSql = implode(" AND ", $conditionClauses);
    
            $sql = "UPDATE $table SET $updateSql WHERE $conditionSql";
            
            try {
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(array_merge(array_values($data), array_values($conditions)));
            } catch (PDOException $e) {
                throw new Exception("SQL execution error: " . $e->getMessage());
            }
        }

        protected function executeSql(string $sql) {
            try {
                $this->pdo->exec($sql);
                $outputDir = __DIR__ . '/../../../core/entities';
                $entities = new HorizonEntity;
                $entities->generateEntities($outputDir);
            } catch (PDOException $e) {
                throw new Exception("SQL execution error: " . $e->getMessage());
            }
        }
    }