<?php

    namespace Horizon\Src\Views\Forms;

    class LoginForm {
        public function form() {
            return [
                'form' => [
                    'action' => '/register',
                    'method' => 'POST',
                    'class' => 'registration-form',
                    'id' => 'registrationForm',
                    'inputs' => [
                        [
                            'type' => 'email',
                            'name' => 'email',
                            'label' => 'email',
                            'class' => 'form-control',
                            'placeholder' => 'test'
                        ],
                        [
                            'type' => 'password',
                            'name' => 'password',
                            'label' => 'password',
                            'class' => 'form-control'
                        ],
                    ],
                    'buttons' => [
                        [
                            'type' => 'submit',
                            'label' => 'Submit',
                            'class' => 'btn btn-primary'
                        ],
                    ]
                ]
            ];
        }
    }