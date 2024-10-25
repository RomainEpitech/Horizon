<?php

    namespace Horizon\Core\Mystic;

    use Exception;
    use Horizon\Core\Database\Database;
    use Horizon\Core\Env\EnvLoader;
    use PDO;

    class Mystic {

        protected $pdo;
        
        public function __construct() {
            try {
                $envLoader = new EnvLoader();
                $envLoader->load();                
    
                if (isset($_ENV['TIMEZONE'])) {
                    date_default_timezone_set($_ENV['TIMEZONE']);
                }
    
                $this->pdo = Database::run()->getConn();
            } catch (Exception $e) {
                throw new Exception("Mystic initialization failed: " . $e->getMessage());
            }
        }
    
        protected function getConnection(): PDO {
            return $this->pdo;
        }
    }