<?php

    namespace Horizon\Core\Guards\Keys;

    use Exception;
    use Horizon\Core\Env\EnvLoader;

    class Token {
        protected static $tokenKey;
        protected static $tokenDuration;

        public static function init() {
            EnvLoader::load('./.env');
            self::$tokenKey = EnvLoader::get('TOKEN_KEY');
            self::$tokenDuration = EnvLoader::get('TOKEN_DURATION');
        }

        public static function generateToken() {
            self::init();
            return hash_hmac('sha256', bin2hex(random_bytes(16)), self::$tokenKey);
        }

        public static function validateToken($token) {
            self::init();
            $expiryTime = strtotime($token['created_at']) + (self::$tokenDuration * 60);
            return time() < $expiryTime;
        }
    }