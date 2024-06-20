<?php

    namespace Horizon\Core\Guards\Auth;

    use Exception;
    use Horizon\Core\Database\Database;
    use Horizon\Core\Entities\Users;
use Horizon\Core\LogHandler;
use Horizon\Core\Mystic\Mystic;

    class Auth {
        protected $db;

        public function __construct() {
            $this->db = Database::getInstance()->getConn();
        }

        public static function hashPassword($password) {
            return password_hash($password, PASSWORD_BCRYPT);
        }

        public static function registerUser($params) {
            if (!isset($params['password']) || !isset($params['confirm_password'])) {
                throw new Exception("Password and Confirm Password fields are required.");
            }
            
            if ($params['password'] !== $params['confirm_password']) {
                throw new Exception("Passwords do not match.");
            }

            $params['password'] = self::hashPassword($params['password']);
            unset($params['confirm_password']);

            $keys = array_keys($params);

            try {
                Mystic::insert(Users::class, $params);
                $log = new LogHandler();
                $log->newUser($params['email']);
                return true;
            } catch (Exception $e) {
                $log->failedNewUser($params['email']);
                throw new Exception("Failed to register user: " . $e->getMessage());
            }
        }
    }