<?php

    namespace Horizon\Core\Guards\Auth;

    use Exception;
    use Horizon\Core\Database\Database;
    use Horizon\Core\Entities\Users;
use Horizon\Core\Guards\Keys\Token;
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
            if (!isset($params['password']) || !isset($params['confirm_password']) || empty($params['password']) || empty($params['confirm_password'])) {
                throw new Exception("Password and Confirm Password fields are required.");
            }
    
            if ($params['password'] !== $params['confirm_password']) {
                throw new Exception("Passwords do not match.");
            }
    
            if (isset($params['email'])) {
                $existingUser = Mystic::fetchOneBy(Users::class, ['email' => $params['email']]);
                if ($existingUser) {
                    throw new Exception("Email already exists.");
                }
            }
    
            $params['password'] = self::hashPassword($params['password']);
            unset($params['confirm_password']);
    
            try {
                Mystic::insert(Users::class, $params);
                $log = new LogHandler();
                $log->newUser($params['email']);
                return true;
            } catch (Exception $e) {
                $log = new LogHandler();
                $log->failedNewUser($params['email']);
                throw new Exception("Failed to register user: " . $e->getMessage());
            }
        }

        public static function loginUser($params) {
            if (!isset($params['email']) || !isset($params['password'])) {
                throw new Exception("Email and Password fields are required.");
            }
    
            $user = Mystic::fetchOneBy(Users::class, ['email' => $params['email']]);
            if (!$user) {
                throw new Exception("Invalid email or password.");
            }
    
            if (!password_verify($params['password'], $user->password)) {
                throw new Exception("Invalid email or password.");
            }
    
            $newToken = Token::generateToken();
            $user->token = $newToken;
            Mystic::update(Users::class, $user->id, ['token' => $newToken]);
    
            return ['token' => $newToken, 'user' => $user];
        }
    }