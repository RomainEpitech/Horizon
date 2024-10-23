<?php

    namespace Horizon\Core\Database;

    use Horizon\Core\Env\EnvLoader;
    use PDO;
    use PDOException;
    use Exception;

    class Database {

        private static $instance = null;
        protected $conn;

        public $name;
        private $password;
        private $username;
        private $charset;
        private $port;
        private $host;

        public function __construct() {
            $this->getEnvVars();
            $this->connect();
        }

        public static function getInstance(): self {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * @throws Exception If a var is missing
         */
        private function getEnvVars(): void {
            $requiredVars = [
                'DB_HOST' => 'host',
                'DB_PORT' => 'port',
                'DB_NAME' => 'name',
                'DB_USERNAME' => 'username',
                'DB_PASSWORD' => 'password',
                'DB_CHARSET' => 'charset'
            ];

            foreach ($requiredVars as $envVar => $property) {
                if (!isset($_ENV[$envVar])) {
                    throw new Exception("Variable d'environnement manquante: {$envVar}");
                }
                $this->$property = $_ENV[$envVar];
            }
        }

        private function connect(): void {
            try {
                $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->name};charset={$this->charset}";
                if ($this->host === 'localhost') {
                    $dsn .= ';unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock';
                }
    
                $this->conn = new PDO($dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new Exception("Database connection error: " . $e->getMessage());
            }
        }

        public function getConn(): PDO {
            return $this->conn;
        }

        /**
         * @return self
         */
        public static function run(): self {
            return self::getInstance();
        }

        public function __clone() {}
        public function __wakeup() {}
    }