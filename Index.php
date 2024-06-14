<?php

    use Horizon\Core\Core;

    require 'vendor/autoload.php';
    require './routes/Web.php';

    $core = new Core();
    $core->run();