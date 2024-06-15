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

        private function getEnvVars() {
            EnvLoader::load('./.env');

            $this->name = EnvLoader::get('DB_NAME');
            $this->password = EnvLoader::get('DB_PASSWORD');
            $this->username = EnvLoader::get('DB_USERNAME');
            $this->charset = EnvLoader::get('DB_CHARSET');
            $this->port = EnvLoader::get('DB_PORT');
            $this->host = EnvLoader::get('DB_HOST');
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

        public function __clone() {}
        public function __wakeup() {}
    }