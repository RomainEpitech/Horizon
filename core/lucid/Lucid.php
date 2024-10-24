<?php

    namespace Horizon\Core\Lucid;

    class Lucid {
        public function render($template, $scope = []) {
            $templatePath = __DIR__ . "/../../src/views/layouts/" . $template . ".lucid.php";
            $layoutPath = __DIR__ . "/../../src/views/index.php";
            
            if (!file_exists($templatePath)) {
                throw new \Exception("Template file not found: $templatePath");
            }

            extract($scope);
            $content = file_get_contents($templatePath);
            $content = $this->parseTemplate($content, $scope);

            ob_start();
            eval('?>' . $content);
            $content = ob_get_clean();

            if (!file_exists($layoutPath)) {
                throw new \Exception("Layout file not found: $layoutPath");
            }

            ob_start();
            include $layoutPath;
            return ob_get_clean();
        }

        public function renderMain($template, $scope = []) {
            $templatePath = __DIR__ . "/../../core/views/" . $template . ".lucid.php";
            $layoutPath = __DIR__ . "/../../src/views/index.php";
            
            if (!file_exists($templatePath)) {
                throw new \Exception("Template file not found: $templatePath");
            }

            extract($scope);
            $content = file_get_contents($templatePath);
            $content = $this->parseTemplate($content, $scope);

            ob_start();
            eval('?>' . $content);
            $content = ob_get_clean();

            if (!file_exists($layoutPath)) {
                throw new \Exception("Layout file not found: $layoutPath");
            }

            ob_start();
            include $layoutPath;
            return ob_get_clean();
        }

        private function parseTemplate($content, $scope) {
            $content = preg_replace('/\{\{\s*(.+?)\s*\}\}/', '<?= $1 ?>', $content);

            // Replace {@form => 'formVariable'} with the rendered form HTML
            $content = preg_replace_callback('/\{@form\s*=>\s*\'(.+?)\'\}/', function($matches) use ($scope) {
                $formVariable = $matches[1];
                return '<?= $' . $formVariable . ' ?>';
            }, $content);

            // Replace {@foreach (...) } and {@endforeach} with PHP code
            $content = preg_replace('/\{@foreach\s*\((.+?)\)\s*\}/', '<?php foreach($1): ?>', $content);
            $content = preg_replace('/\{@endforeach\s*\}/', '<?php endforeach; ?>', $content);

            // Replace {@if (...)} and {@endif} with PHP code
            $content = preg_replace('/\{@if\s*\((.+?)\)\s*\}/', '<?php if($1): ?>', $content);
            $content = preg_replace('/\{@elseif\s*\((.+?)\)\s*\}/', '<?php elseif($1): ?>', $content);
            $content = preg_replace('/\{@else\s*\}/', '<?php else: ?>', $content);
            $content = preg_replace('/\{@endif\s*\}/', '<?php endif; ?>', $content);

            // Replace {@include 'filename'} with the included file's content
            $content = preg_replace_callback('/\{@include\s*\'(.+?)\'\}/', function($matches) use ($scope) {
                $filename = $matches[1];
                $filePath = __DIR__ . "/../../src/views/layouts/components/" . $filename . ".lucid.php";

                if (!file_exists($filePath)) {
                    throw new \Exception("Included file not found: $filePath");
                }

                $includedContent = file_get_contents($filePath);
                return $this->parseTemplate($includedContent, $scope);
            }, $content);
            
            // Replace {@ var } with value
            $content = preg_replace_callback('/\{@\s*(.+?)\s*\}/', function($matches) {
                $variable = $matches[1];
                return '<?= ' . $variable . ' ?>';
            }, $content);

            return $content;
        }
    }