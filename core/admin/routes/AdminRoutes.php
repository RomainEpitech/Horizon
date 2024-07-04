<?php

    use Horizon\Core\Admin\Controller\AdminDashboardController;
    use Horizon\Core\Guards\Middleware\RoleMiddleware;
    use Horizon\Core\Router\AdminRouter;

    AdminRouter::get('/dashboard', [AdminDashboardController::class => "index"], [[RoleMiddleware::class, ['Admin']]]);
