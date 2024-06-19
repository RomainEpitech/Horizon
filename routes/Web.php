<?php

    use Horizon\Core\Router\Router;
    use Horizon\Src\Controllers\HomeController;
    use Horizon\Src\Controllers\RegisterController;

    Router::get('/', [HomeController::class => 'index']);
    Router::post('/register', [RegisterController::class => 'index']);