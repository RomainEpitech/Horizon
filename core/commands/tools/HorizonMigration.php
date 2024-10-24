<?php

    namespace Horizon\Core\Commands\Tools;

    use Horizon\Core\Commands\CommandHandler;
    use Horizon\Core\Database\Database;
    use PDO;

    class HorizonMigration extends CommandHandler {

        public function newMigration() {
            $timestamp = date('YmdHis');
            $filePath = __DIR__ . "/../../../migrations/Version{$timestamp}.php";
            $migrationTemplate = "<?php\n\n    use Horizon\Core\Commands\Migrations\AbstractMigration;\n\n    class Version{$timestamp} extends AbstractMigration {\n\n        public function up() {\n            //\n        }\n\n        public function down() {\n            //\n        }\n    }";

            if (!file_exists(dirname($filePath))) {
                mkdir(dirname($filePath), 0777, true);
            }

            file_put_contents($filePath, $migrationTemplate);
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