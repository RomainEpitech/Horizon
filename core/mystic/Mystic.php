<?php

    namespace Horizon\Core\Mystic;

    use Exception;
    use Horizon\Core\Database\Database;
    use Horizon\Core\Env\EnvLoader;
    use Horizon\Core\Mystic\Queries\Table;
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
         * @return string JSON
         */
        public static function all(): string {
            $table = new Table(
                Database::run()->getConn(),
                static::getTableName(),
                get_called_class()
            );
            return $table->get();
        }

        /**
         * Recherche par critères
         * @param array $criteria
         * @return Table
         */
        public static function findBy(array $criteria): Table {
            $table = new Table(
                Database::run()->getConn(),
                static::getTableName(),
                get_called_class()
            );
            return $table->where($criteria);
        }

        /**
         * Recherche tous les enregistrements correspondant aux critères
         * @param array $criteria
         * @return string JSON
         */
        public static function findAllBy(array $criteria): string {
            return static::findBy($criteria)->get();
        }

        /**
         * Trouve un seul enregistrement correspondant aux critères
         * @param array $criteria
         * @return string JSON
         */
        public static function findOneBy(array $criteria): string {
            return static::findBy($criteria)->first();
        }
    }