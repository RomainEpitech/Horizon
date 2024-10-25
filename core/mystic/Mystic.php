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

        protected static function getTableName(): string {
            $instance = new (get_called_class())();
            if (property_exists($instance, 'table')) {
                return $instance->table;
            }
            
            $className = (new \ReflectionClass(get_called_class()))->getShortName();
            return strtolower($className) . 's';
        }

        /**
         * Retourne toutes les entrées de la table en JSON
         * @return string
         */
        public static function findAll(): string {
            try {
                $pdo = Database::run()->getConn();
                $table = static::getTableName();
                
                $stmt = $pdo->prepare("SELECT * FROM {$table}");
                $stmt->execute();
                
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                return json_encode($results, JSON_THROW_ON_ERROR);
            } catch (Exception $e) {
                throw new Exception("Error in findAll(): " . $e->getMessage());
            }
        }

        /**
         * Retourne toutes les entrées de la table sous forme d'instances du modèle
         * @return array
         */
        public static function all(): array {
            try {
                $pdo = Database::run()->getConn();
                $table = static::getTableName();
                $className = get_called_class();
                
                $stmt = $pdo->prepare("SELECT * FROM {$table}");
                $stmt->execute();
                
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $instances = [];
                
                foreach ($results as $result) {
                    $instance = new $className();
                    foreach ($result as $key => $value) {
                        $setter = 'set' . str_replace('_', '', ucwords($key, '_'));
                        if (method_exists($instance, $setter)) {
                            if ($key === 'created_at' && $value !== null) {
                                $value = new \DateTime($value);
                            }
                            if ($key === 'role' && is_string($value)) {
                                $value = json_decode($value, true);
                            }
                            $instance->$setter($value);
                        }
                    }
                    $instances[] = $instance;
                }
                
                return $instances;
            } catch (Exception $e) {
                throw new Exception("Error in all(): " . $e->getMessage());
            }
        }
    }