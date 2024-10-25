<?php

    namespace Horizon\Core\Commands\Tools;

    use Horizon\Core\Commands\CommandHandler;
    use Horizon\Core\Database\Database;
    use Horizon\Core\Env\EnvLoader;
    use Horizon\Core\Inc\Success;
    use Horizon\Core\Inc\Error;
    use PDO;
    use Exception;
    use Horizon\Core\Commands\Migrations\Schema;
    use Horizon\Core\Logs\Log;

    class HorizonMigration extends CommandHandler {
        private $db;

        public function __construct() {
            $envLoader = new EnvLoader();
            $envLoader->load();

            $this->initializeMigrationsTable();
        }

        private function initializeMigrationsTable() {
            try {
                $this->db = Database::getInstance();
                $pdo = $this->db->getConn();

                $stmt = $pdo->query("SHOW TABLES LIKE 'migrations'");
                if ($stmt->rowCount() === 0) {
                    $sql = "CREATE TABLE migrations (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        migration VARCHAR(255) NOT NULL,
                        executed_at DATETIME NOT NULL
                    )";
                    $pdo->exec($sql);
                    Success::displaySuccessMessage("Migrations table created successfully.");
                }
            } catch (Exception $e) {
                Error::displayErrorMessage("Database error: " . $e->getMessage());
                exit(1);
            }
        }

        public function newMigration(?string $name = null) {
            try {
                $timestamp = date('YmdHis');
                $className = $name 
                    ? 'Version' . $timestamp . ucfirst($name)
                    : 'Version' . $timestamp;
                $filePath = __DIR__ . "/../../../database/migrations/{$className}.php";
                
                $migrationTemplate = <<<PHP
        <?php
        
        namespace Horizon\\Database\\Migrations;
        
        use Horizon\Core\Commands\Migrations\AbstractMigration;
        use Horizon\Core\Commands\Migrations\Schema;
        
        class {$className} extends AbstractMigration {
        
            public function up(): void {
                // Create table here
            }
        
            public function down(): void {
                // Revert migration here
            }
        }
        PHP;
        
                if (!file_exists(dirname($filePath))) {
                    mkdir(dirname($filePath), 0777, true);
                }
        
                file_put_contents($filePath, $migrationTemplate);
                Success::displaySuccessMessage("New migration created: {$className}.php");
                Log::info("New migration " . $className . " created successfully.");
            } catch (Exception $e) {
                Error::displayErrorMessage("Failed to create migration: " . $e->getMessage());
                Log::alert("Failed to create migration " . $className);
                exit(1);
            }
        }

        public function makeMigration() {
            try {
                $migrationDir = './database/migrations';
                
                if (!is_dir($migrationDir)) {
                    Error::displayErrorMessage("Migrations directory not found.");
                    return;
                }
        
                $allMigrationFiles = $this->getAllMigrationFiles($migrationDir);
        
                if (empty($allMigrationFiles)) {
                    Error::displayErrorMessage("No migration files found.");
                    return;
                }
        
                $pendingMigrations = $this->getPendingMigrations($allMigrationFiles);
        
                if (empty($pendingMigrations)) {
                    Success::displaySuccessMessage("No migration to run.");
                    return;
                }
        
                $pdo = $this->db->getConn();
                Schema::setPdo($pdo);
        
                $hasError = false;
        
                foreach ($pendingMigrations as $migrationFile) {
                    $className = pathinfo($migrationFile, PATHINFO_FILENAME);
                    $fullClassName = "Horizon\\Database\\Migrations\\{$className}";
                    $migrationPath = $migrationDir . '/' . $migrationFile;
        
                    if (!file_exists($migrationPath)) {
                        Error::displayErrorMessage("Migration file not found: $migrationFile");
                        continue;
                    }
        
                    require_once $migrationPath;
        
                    if (!class_exists($fullClassName)) {
                        Error::displayErrorMessage("Migration class $fullClassName not found.");
                        continue;
                    }
        
                    try {
                        $migration = new $fullClassName($pdo);
                        $migration->up();
                        $this->logMigration($className);
                        
                        Success::displaySuccessMessage("Migration $className executed successfully.");
                        Log::success("Migration $className ran successfully.");
                        
                    } catch (\Exception $e) {
                        $hasError = true;
                        Error::displayErrorMessage("Failed to execute migration $className: " . $e->getMessage());
                        Log::error("Failed to run migration $className: " . $e->getMessage());
                    }
                }
        
                if (!$hasError) {
                    Success::displaySuccessMessage("All migrations completed successfully.");
                }
        
            } catch (\Exception $e) {
                Error::displayErrorMessage("Migration error: " . $e->getMessage());
                Log::error("Error running migration: " . $e->getMessage());
            }
        }

        private function createMigrationsTableIfNotExists(): void {
            $pdo = $this->db->getConn();
            
            $sql = "CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            
            $pdo->exec($sql);
        }

        private function getAllMigrationFiles($directory) {
            $files = scandir($directory);
            $migrationFiles = [];
    
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                    $migrationFiles[] = $file;
                }
            }
    
            sort($migrationFiles);
            return $migrationFiles;
        }

        private function getPendingMigrations($allMigrationFiles) {
            $executedMigrations = $this->getExecutedMigrations();
            $pendingMigrations = [];
        
            foreach ($allMigrationFiles as $file) {
                $migrationName = pathinfo($file, PATHINFO_FILENAME);
                if (!in_array($migrationName, $executedMigrations)) {
                    $pendingMigrations[] = $file;
                }
            }
        
            return $pendingMigrations;
        }

        private function getExecutedMigrations() {
            $pdo = $this->db->getConn();
            $stmt = $pdo->query("SELECT migration FROM migrations ORDER BY id ASC");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        private function logMigration($migrationName) {
            $pdo = $this->db->getConn();
            $stmt = $pdo->prepare("INSERT INTO migrations (migration, executed_at) VALUES (:migration, :executed_at)");
            $stmt->execute([
                'migration' => $migrationName,
                'executed_at' => date('Y-m-d H:i:s')
            ]);
        }

        private function migrationExists($migrationName) {
            $pdo = $this->db->getConn();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM migrations WHERE migration = :migration");
            $stmt->execute(['migration' => $migrationName]);
            return $stmt->fetchColumn() > 0;
        }

        public function revertMigration(?string $version = null): void {
            try {
                $pdo = $this->db->getConn();
                Schema::setPdo($pdo);
        
                if (!$this->confirmRevert($version)) {
                    Error::displayErrorMessage("Operation cancelled.");
                    return;
                }
        
                if ($version) {
                    $this->revertSingleMigration($version);
                } else {
                    $this->revertLastMigration();
                }
        
            } catch (\Exception $e) {
                Error::displayErrorMessage("Revert error: " . $e->getMessage());
                Log::error("Error reverting migration: " . $e->getMessage());
            }
        }
        
        private function confirmRevert(?string $version): bool {
            $migrationText = $version ?? "the last migration";
            echo "\033[33m";
            echo "⚠️  Warning: You are about to revert {$migrationText}.\n";
            echo "This operation might result in data loss.\n";
            echo "\033[0m";
            
            echo "Are you sure you want to continue? [y/N]: ";
            $handle = fopen("php://stdin", "r");
            $line = fgets($handle);
            fclose($handle);
            
            return strtolower(trim($line)) === 'y';
        }
        
        private function revertLastMigration(): void {
            $pdo = $this->db->getConn();
            
            $stmt = $pdo->query("SELECT migration FROM migrations ORDER BY id DESC LIMIT 1");
            $lastMigration = $stmt->fetchColumn();
            
            if (!$lastMigration) {
                Success::displaySuccessMessage("No migrations to revert.");
                return;
            }
            
            $this->revertSingleMigration($lastMigration);
        }
        
        private function revertSingleMigration(string $version): void {
            $migrationDir = './database/migrations';
            $migrationFile = $migrationDir . '/' . $version . '.php';
        
            if (!$this->migrationExists($version)) {
                Error::displayErrorMessage("Migration $version not found in the database.");
                return;
            }

            if (!file_exists($migrationFile)) {
                Error::displayErrorMessage("Migration file not found: $version.php");
                return;
            }
        
            require_once $migrationFile;
            $fullClassName = "Horizon\\Database\\Migrations\\{$version}";
        
            if (!class_exists($fullClassName)) {
                Error::displayErrorMessage("Migration class $fullClassName not found.");
                return;
            }
        
            try {
                $migration = new $fullClassName($this->db->getConn());
                $migration->down();
                $this->removeMigrationFromLog($version);
                
                Success::displaySuccessMessage("Migration $version reverted successfully.");
                Log::success("Migration $version reverted successfully.");
                
            } catch (\Exception $e) {
                Error::displayErrorMessage("Failed to revert migration $version: " . $e->getMessage());
                Log::error("Failed to revert migration $version: " . $e->getMessage());
            }
        }
        
        private function removeMigrationFromLog(string $migration): void {
            $pdo = $this->db->getConn();
            $stmt = $pdo->prepare("DELETE FROM migrations WHERE migration = :migration");
            $stmt->execute(['migration' => $migration]);
        }
    }