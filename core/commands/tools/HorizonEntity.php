<?php

    namespace Horizon\Core\Commands\Tools;

    use Horizon\Core\Commands\CommandHandler;
    use Horizon\Core\Database\Database;
    use Horizon\Core\LogHandler;
    use PDO;

    class HorizonEntity extends CommandHandler {
        private $pdo;

        public function __construct() {
            $db = Database::getInstance();
            $this->pdo = $db->getConn();
        }

        public function generateEntities($outputDir) {
            $tables = $this->getTables();

            foreach ($tables as $table) {
                $columns = $this->getColumns($table);
                $entityCode = $this->generateEntityCode($table, $columns);
                file_put_contents("$outputDir/" . ucfirst($table) . ".php", $entityCode);
            }
            $this->displaySuccessMessage("Entities created successfully.");
            $log = new LogHandler();
            $log->entitiesLog();
        }

        private function getTables() {
            $stmt = $this->pdo->query("SHOW TABLES");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        private function getColumns($table) {
            $stmt = $this->pdo->query("SHOW COLUMNS FROM $table");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        private function generateEntityCode($table, $columns) {
            $className = ucfirst($table);
            $properties = "";
            $methods = "";

            foreach ($columns as $column) {
                $propName = $column['Field'];
                $propType = $this->mapColumnTypeToPhpType($column['Type']);

                $properties .= "\n        public \$$propName;\n";

                $methods .= "\n        public function get" . ucfirst($propName) . "() {\n";
                $methods .= "            return \$this->$propName;\n";
                $methods .= "        }\n";

                $methods .= "\n        public function set" . ucfirst($propName) . "(\$$propName) {\n";
                $methods .= "            \$this->$propName = \$$propName;\n";
                $methods .= "        }\n";
            }

            $entityCode = "<?php\n\n    namespace Horizon\Core\Entities;\n\n    use Horizon\Core\Mystic\Mystic;\n\n";
            $entityCode .= "    class $className extends Mystic {\n        protected static \$tableName = '$className';\n";
            $entityCode .= $properties;
            $entityCode .= $methods;
            $entityCode .= "    }\n";

            return $entityCode;
        }

        private function mapColumnTypeToPhpType($columnType) {
            if (strpos($columnType, 'int') !== false) {
                return 'int';
            } elseif (strpos($columnType, 'char') !== false || strpos($columnType, 'text') !== false) {
                return 'string';
            } elseif (strpos($columnType, 'float') !== false || strpos($columnType, 'double') !== false || strpos($columnType, 'decimal') !== false) {
                return 'float';
            } elseif (strpos($columnType, 'bool') !== false) {
                return 'bool';
            } else {
                return 'mixed';
            }
        }
    }