<?php

    namespace Horizon\Core\Commands\Tools;

    use Horizon\Core\Commands\CommandHandler;
    use Horizon\Core\Database\Database;
    use Horizon\Core\Env\EnvLoader;
    use Horizon\Core\Inc\Success;
    use Horizon\Core\Inc\Error;
    use PDO;
    use Exception;

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
                $filePath = __DIR__ . "/../../../migrations/{$className}.php";
                $migrationTemplate = "<?php\n\n    use Horizon\Core\Commands\Migrations\AbstractMigration;\n\n    class {$className} extends AbstractMigration {\n\n        public function up() {\n            //\n        }\n\n        public function down() {\n            //\n        }\n    }";

                if (!file_exists(dirname($filePath))) {
                    mkdir(dirname($filePath), 0777, true);
                }

                file_put_contents($filePath, $migrationTemplate);
                Success::displaySuccessMessage("New migration created: Version{$className}.php");
            } catch (Exception $e) {
                Error::displayErrorMessage("Failed to create migration: " . $e->getMessage());
                exit(1);
            }
        }

        public function makeMigration() {
            try {
                $migrationDir = __DIR__ . '/../../../migrations';
                
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
    
                foreach ($pendingMigrations as $migrationFile) {
                    $className = pathinfo($migrationFile, PATHINFO_FILENAME);
                    $migrationName = 'migrations/' . $className;
    
                    require_once $migrationDir . '/' . $migrationFile;
    
                    if (!class_exists($className)) {
                        Error::displayErrorMessage("Migration class $className not found.");
                        continue;
                    }
    
                    $migration = new $className($pdo);
    
                    if (!method_exists($migration, 'up')) {
                        Error::displayErrorMessage("Migration $className does not have an up method.");
                        continue;
                    }
    
                    $pdo->beginTransaction();
    
                    try {
                        $migration->up();
                        $this->logMigration($migrationName);
                        $pdo->commit();
                        Success::displaySuccessMessage("Migration $className executed successfully.");
                    } catch (Exception $e) {
                        $pdo->rollBack();
                        Error::displayErrorMessage("Failed to execute migration $className: " . $e->getMessage());
                    }
                }
    
            } catch (Exception $e) {
                Error::displayErrorMessage("Migration error: " . $e->getMessage());
                exit(1);
            }
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
                $migrationName = 'migrations/' . pathinfo($file, PATHINFO_FILENAME);
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
    }