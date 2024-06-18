<?php

    namespace Horizon\Core\Templating;

    class Lucid {
        public function render($template, $scope = []) {
            $templatePath = __DIR__ . "/../../src/views/layouts/" . $template . ".lucid.php";
            if (!file_exists($templatePath)) {
                throw new \Exception("Template file not found: $templatePath");
            }

            extract($scope);
            $content = file_get_contents($templatePath);
            $content = $this->parseTemplate($content, $scope);

            ob_start();
            eval('?>' . $content);
            return ob_get_clean();
        }

        private function parseTemplate($content, $scope) {
            $content = preg_replace('/\{\{\s*(.+?)\s*\}\}/', '<?= $1 ?>', $content);

            // Replace {@form => 'formVariable'} with the rendered form HTML
            $content = preg_replace_callback('/\{@form\s*=>\s*\'(.+?)\'\}/', function($matches) use ($scope) {
                $formVariable = $matches[1];
                return '<?= $' . $formVariable . ' ?>';
            }, $content);

            // Replace @foreach and @endforeach with PHP code
            $content = preg_replace('/@foreach\s*\((.+?)\)/', '<?php foreach($1): ?>', $content);
            $content = preg_replace('/@endforeach/', '<?php endforeach; ?>', $content);

            // Replace @if, @elseif, @else, and @endif with PHP code
            $content = preg_replace('/@if\s*\((.+?)\)/', '<?php if($1): ?>', $content);
            $content = preg_replace('/@elseif\s*\((.+?)\)/', '<?php elseif($1): ?>', $content);
            $content = preg_replace('/@else/', '<?php else: ?>', $content);
            $content = preg_replace('/@endif/', '<?php endif; ?>', $content);

            return $content;
        }

        public function renderForm($formClass) {
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
