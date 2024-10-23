<?php

    use Horizon\Core\Core;

    session_start();
    
    require './vendor/autoload.php';
    require './routes/Web.php';

    $core = new Core();
    $core->run();