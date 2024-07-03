<?php

    namespace Horizon\Core\Guards\Middleware;

    use Horizon\Core\Guards\Keys\Roles;
    use Horizon\Core\Guards\Http\Request;

    class RoleMiddleware {
        public static function handle(Request $request, ...$requiredRoles) {
            if (!Roles::hasRole($requiredRoles)) {
                http_response_code(403);
                echo json_encode(["error" => "Access denied"]);
                return false;
            }
            return true;
        }
    }
