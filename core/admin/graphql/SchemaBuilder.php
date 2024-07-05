<?php

    namespace Horizon\Core\Admin\GraphQL;

    use GraphQL\Type\Schema;
    use GraphQL\Type\Definition\ObjectType;
    use GraphQL\Type\Definition\Type;
    use GraphQL\Type\Definition\ResolveInfo;
    use Horizon\Core\Mystic\Mystic;
    use Horizon\Core\Entities\Users;

    class SchemaBuilder {
        public static function build() {
            $queryType = new ObjectType([
                'name' => 'Query',
                'fields' => [
                    'users' => [
                        'type' => Type::listOf(self::userType()),
                        'resolve' => function($rootValue, $args, $context, ResolveInfo $info) {
                            return Mystic::fetchAll(Users::class);
                        }
                    ],
                ],
            ]);

            return new Schema([
                'query' => $queryType,
            ]);
        }

        private static function userType() {
            return new ObjectType([
                'name' => 'User',
                'fields' => [
                    'id' => Type::nonNull(Type::id()),
                    'email' => Type::nonNull(Type::string()),
                    'role' => Type::nonNull(Type::string()),
                    'created_at' => Type::nonNull(Type::string()),
                ],
            ]);
        }
    }
