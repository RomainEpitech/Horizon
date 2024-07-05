<?php

    namespace Horizon\Core\Admin\GraphQL;

    use GraphQL\Type\Definition\Type;
    use GraphQL\Type\Definition\ObjectType;

    class Types {
        private static $user;

        public static function user() {
            return self::$user ?: (self::$user = new ObjectType([
                'name' => 'User',
                'fields' => [
                    'id' => Type::nonNull(Type::id()),
                    'email' => Type::nonNull(Type::string()),
                    'role' => Type::nonNull(Type::string()),
                    'created_at' => Type::nonNull(Type::string()),
                ]
            ]));
        }
    }
