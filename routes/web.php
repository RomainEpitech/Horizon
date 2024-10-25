<?php

    use Horizon\App\Controllers\HomeController;
    use Horizon\Core\Router\Routes;

    Routes::get('/', [HomeController::class => 'renderHome']);