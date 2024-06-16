<?php

    namespace Horizon\Core\Commands\Tools;

    use Horizon\Core\Commands\CommandHandler;
    use Horizon\Core\Database\Database;
    use Horizon\Core\LogHandler;
    use PDO;

    class HorizonMigration extends CommandHandler {

        public function newMigration() {
            $timestamp = date('YmdHis');
            $filePath = __DIR__ . "/../../../migrations/Version{$timestamp}.php";
            $migrationTemplate = "<?php\n\n    use Horizon\Core\Commands\Migration\AbstractMigration;\n\n    class Version{$timestamp} extends AbstractMigration {\n\n        public function up() {\n            //\n        }\n\n        public function down() {\n            //\n        }\n    }";

            if (!file_exists(dirname($filePath))) {
                mkdir(dirname($filePath), 0777, true);
            }

            file_put_contents($filePath, $migrationTemplate);
            $log = new LogHandler();
            $log->newMigration($timestamp);
            $this->displaySuccessMessage("Migration created successfully.");
        }

        public function makeMigration() {
            $migrationDir = __DIR__ . '/../../../migrations';
            $latestFile = $this->getLatestMigrationFile($migrationDir);

            if ($latestFile) {
                $className = pathinfo($latestFile, PATHINFO_FILENAME);
                $migrationName = 'migrations/' . $className;

                if ($this->migrationExists($migrationName)) {
                    $this->displayErrorMessage("Migration $className has already been executed.");
                    $log = new LogHandler();
                    $log->failedMigration($className);
                    return;
                }

                require_once $migrationDir . '/' . $latestFile;

                if (class_exists($className)) {
                    $db = Database::getInstance();
                    $pdo = $db->getConn();

                    $migration = new $className($pdo);
                    if (method_exists($migration, 'up')) {
                        try {
                            $migration->up();
                            $migration->down();
                            $this->logMigration($migrationName);
                            $this->displaySuccessMessage("Migration $className executed successfully.");
                            $log = new LogHandler();
                            $log->makeMigration($className);
                        } catch (\Exception $e) {
                            $this->displayErrorMessage("Failed to execute migration $className: " . $e->getMessage() . "\n");
                        }
                    } else {
                        $this->displayErrorMessage("Migration $className does not have an up method.");
                    }
                } else {
                    $this->displayErrorMessage("Migration class $className not found.");
                }
            } else {
                $this->displayErrorMessage("No migration files found.");
            }
        }

        private function getLatestMigrationFile($directory) {
            $files = scandir($directory, SCANDIR_SORT_DESCENDING);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                    return $file;
                }
            }
            return null;
        }

        private function logMigration($migrationName) {
            $db = Database::getInstance();
            $pdo = $db->getConn();

            $stmt = $pdo->prepare("INSERT INTO migrations (migration, executed_at) VALUES (:migration, :executed_at)");
            $stmt->execute([
                'migration' => $migrationName,
                'executed_at' => date('Y-m-d H:i:s')
            ]);
        }

        private function migrationExists($migrationName) {
            $db = Database::getInstance();
            $pdo = $db->getConn();

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM migrations WHERE migration = :migration");
            $stmt->execute(['migration' => $migrationName]);
            return $stmt->fetchColumn() > 0;
        }
    }