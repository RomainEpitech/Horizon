<?php

    namespace Horizon\Core\Commands\Migrations;

    use PDO;

    class Blueprint {
        protected $tableName;
        protected $columns = [];
        protected $commands = [];
        protected $isModification;
    
        public function __construct(string $tableName, bool $isModification = false) {
            $this->tableName = $tableName;
            $this->isModification = $isModification;
        }
    
        public function id(string $columnName = 'id'): ColumnDefinition {
            return $this->unsignedBigInteger($columnName)->autoIncrement()->primary();
        }
    
        public function bigInteger(string $columnName): ColumnDefinition {
            return $this->addColumn('bigint', $columnName);
        }
    
        public function unsignedBigInteger(string $columnName): ColumnDefinition {
            return $this->addColumn('bigint', $columnName)->unsigned();
        }
    
        public function integer(string $columnName): ColumnDefinition {
            return $this->addColumn('int', $columnName);
        }
    
        public function tinyInteger(string $columnName): ColumnDefinition {
            return $this->addColumn('tinyint', $columnName);
        }
    
        public function string(string $columnName, int $length = 255): ColumnDefinition {
            return $this->addColumn('varchar', $columnName, $length);
        }
    
        public function char(string $columnName, int $length = 255): ColumnDefinition {
            return $this->addColumn('char', $columnName, $length);
        }
    
        public function text(string $columnName): ColumnDefinition {
            return $this->addColumn('text', $columnName);
        }
    
        public function mediumText(string $columnName): ColumnDefinition {
            return $this->addColumn('mediumtext', $columnName);
        }
    
        public function longText(string $columnName): ColumnDefinition {
            return $this->addColumn('longtext', $columnName);
        }
    
        public function json(string $columnName): ColumnDefinition {
            return $this->addColumn('json', $columnName);
        }
    
        public function boolean(string $columnName): ColumnDefinition {
            return $this->addColumn('tinyint', $columnName, 1);
        }
    
        public function date(string $columnName): ColumnDefinition {
            return $this->addColumn('date', $columnName);
        }
    
        public function dateTime(string $columnName): ColumnDefinition {
            return $this->addColumn('datetime', $columnName);
        }
    
        public function timestamp(string $columnName): ColumnDefinition {
            return $this->addColumn('timestamp', $columnName);
        }
    
        public function timestamps(): void {
            $this->timestamp('created_at')->nullable();
            $this->timestamp('updated_at')->nullable();
        }
    
        public function softDeletes(): void {
            $this->timestamp('deleted_at')->nullable();
        }
    
        protected function addColumn(string $type, string $name, ?int $length = null): ColumnDefinition {
            $column = new ColumnDefinition($type, $name, $length);
            $this->columns[] = $column;
            return $column;
        }
    
        public function build(PDO $pdo): void {
            if ($this->isModification) {
                $this->buildAlterTable($pdo);
                return;
            }
    
            $sql = $this->buildCreateTable();
            $pdo->exec($sql);
        }
    
        protected function buildCreateTable(): string {
            $sql = "CREATE TABLE `{$this->tableName}` (\n";
            
            foreach ($this->columns as $column) {
                $sql .= "    " . $column->toSql() . ",\n";
            }
            
            $sql = rtrim($sql, ",\n");
            
            $sql .= "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            
            return $sql;
        }
    
        protected function buildAlterTable(PDO $pdo): void {
            $alterations = [];
            
            foreach ($this->columns as $column) {
                $alterations[] = "ADD " . $column->toSql();
            }
            
            if (!empty($alterations)) {
                $sql = "ALTER TABLE `{$this->tableName}` \n" . implode(",\n", $alterations);
                $pdo->exec($sql);
            }
        }
    }