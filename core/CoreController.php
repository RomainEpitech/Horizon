<?php

    namespace Horizon\Core;

    use Horizon\Core\Lucid\Lucid;

    class CoreController {
        protected $lucid;

        public function __construct() {
            $this->lucid = new Lucid;
        }

        protected function render($view, $scope = []) {
            echo $this->lucid->render($view, $scope);
        }

        protected function renderMain($view, $scope = []) {
            echo $this->lucid->renderMain($view, $scope);
        }
    }