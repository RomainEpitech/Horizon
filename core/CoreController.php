<?php

    namespace Horizon\Core;

    use Horizon\Core\Templating\Lucid;

    class CoreController {

        protected $lucid;

        public function __construct() {
            $this->lucid = new Lucid();
        }

        protected function render($view, $scope = []) {
            echo $this->lucid->render($view, $scope);
        }

        protected function renderForm($formClass) {
            return $this->lucid->renderForm($formClass);
        }

        protected function renderAdmin($view, $scope = []) {
            echo $this->lucid->renderAdmin($view, $scope);
        }
    }