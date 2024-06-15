<?php

    namespace Horizon\Core;

    class CoreController {
        protected function render($view, $scope = []) {
            $viewPath = __DIR__ . "/../src/views/layouts/" . $view . ".php";
            $layoutPath = __DIR__ . "/../src/views/index.php";

            extract($scope);
            ob_start();

            if (file_exists($viewPath)) {
                include $viewPath;
            } else {
                echo "View file not found: $viewPath";
            }

            $content = ob_get_clean();
            if (file_exists($layoutPath)) {
                include $layoutPath;
            } else {
                echo "Layout file not found: $layoutPath";
            }
        }
    }