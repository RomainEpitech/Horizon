<?php

    namespace Horizon\Core\Admin\GraphQL;

    use Horizon\Core\Mystic\Mystic;
    use Horizon\Core\Entities\Users;

    class Resolvers {
        public static function getUsers() {
            return Mystic::fetchAll(Users::class);
        }
    }
