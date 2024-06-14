<?php

    use Horizon\Core\Router\Router;
    use Horizon\Src\Controllers\HomeController;

    Router::get('/', [HomeController::class => 'index']);