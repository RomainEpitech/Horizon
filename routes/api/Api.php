<?php

    use Horizon\Core\Router\ApiRouter;
    use Horizon\Src\Controllers\HomeController;

    ApiRouter::get('/', [HomeController::class => 'api']);