<?php

    namespace Horizon\Core;

    use Horizon\Core\Env\EnvLoader;

    class Core {

        public function run() {
            $loader = new EnvLoader();
            $loader->load();
            echo "it is running" . $_ENV['TEST_VAR'];
        }
    }