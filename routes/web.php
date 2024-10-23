<?php

    use Horizon\Core\Router\Routes;
    use Horizon\Src\Controllers\HomeController;

    Routes::get('/', [HomeController::class => 'renderHome']);