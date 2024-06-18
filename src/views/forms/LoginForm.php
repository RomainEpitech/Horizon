<?php

    namespace Horizon\Src\Views\Forms;

    class LoginForm {
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