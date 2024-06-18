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

        protected function renderForm($formClass) {
            if (!class_exists($formClass)) {
                return "Form class not found: $formClass";
            }
    
            $formInstance = new $formClass();
            if (!method_exists($formInstance, 'form')) {
                return "Form method not found in class: $formClass";
            }
    
            $form = $formInstance->form();
            return $this->generateFormHtml($form);
        }

        private function generateFormHtml($formConfig) {
            ob_start();
            ?>
            <form action="" method="post">
                <?php foreach ($formConfig['inputs'] as $input): ?>
                    <div class="form-group">
                        <?php if (isset($input['label'])): ?>
                            <label for="<?= $input['name'] ?>"><?= $input['label'] ?></label>
                        <?php endif; ?>
                        <input
                            type="<?= $input['type'] ?>"
                            name="<?= $input['name'] ?>"
                            class="<?= $input['class'] ?>"
                            <?php if ($input['type'] === 'submit'): ?>
                                value="<?= $input['value'] ?>"
                            <?php endif; ?>
                        />
                    </div>
                <?php endforeach; ?>
            </form>
            <?php
            return ob_get_clean();
        }
    }