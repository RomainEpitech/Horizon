<?php

    namespace Horizon\Core\Guards\Keys;

    class Roles {
        public static function hasRole($requiredRoles) {

            if (!isset($_SESSION['current_User']['role']) || is_null($_SESSION['current_User']['role'])) {
                return false;
            }

            $userRoles = json_decode($_SESSION['current_User']['role'], true);

            foreach ($requiredRoles as $role) {
                if (in_array($role, $userRoles)) {
                    return true;
                }
            }

            return false;
        }
    }
