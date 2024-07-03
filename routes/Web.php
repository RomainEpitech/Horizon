<?php

    use Horizon\Core\Guards\Middleware\RoleMiddleware;
    use Horizon\Core\Router\Router;
    use Horizon\Src\Controllers\HomeController;
    use Horizon\Src\Controllers\RegisterController;

    Router::get('/', [HomeController::class => 'index']);
    Router::post('/register', [RegisterController::class => 'index']);
    Router::post('/login', [HomeController::class => 'login']);
    Router::get('/log', [HomeController::class => 'verify'], [[RoleMiddleware::class, ['User', 'Admin']]]);