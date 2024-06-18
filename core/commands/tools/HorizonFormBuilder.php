<?php

    namespace Horizon\Core\Commands\Tools;

    use Horizon\Core\Commands\CommandHandler;
    use Horizon\Core\LogHandler;

    class HorizonFormBuilder extends CommandHandler {
        public function createForm($formName) {
            $formDir = __DIR__ . '/../../../src/views/forms/';
            $formFile = $formDir . $formName . 'Form.php';

            if (!is_dir($formDir)) {
                mkdir($formDir, 0755, true);
            }

            if (file_exists($formFile)) {
                $this->displayErrorMessage("$formName Form already exist");
                return;
            }

            $formTemplate = $this->generateFormTemplate($formName);

            file_put_contents($formFile, $formTemplate);
            $this->displaySuccessMessage("$formName Form create successfully");
            $log = new LogHandler();
            $log->newForm($formName);
        }

        private function generateFormTemplate($formName){
            $className = ucfirst($formName) . 'Form';
            return <<<EOT
                <?php

                    namespace Horizon\\Src\\Views\\Forms;

                    class $className {
                        public function form() {
                            return [
                                'inputs' => [
                                    [
                                        'type' => 'text',
                                        'name' => 'name',
                                        'label' => 'Name',
                                        'class' => 'form-control'
                                    ],
                                    // Add more inputs here
                                ]
                            ];
                        }
                    }
                EOT;
        }
    }