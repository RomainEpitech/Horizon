<?php

    namespace Horizon\Core\Commands\Tools;

    use Horizon\Core\Commands\CommandHandler;
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
    }