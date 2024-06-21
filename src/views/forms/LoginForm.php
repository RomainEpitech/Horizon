<?php

    namespace Horizon\Src\Views\Forms;

    class LoginForm {
        public function form() {
            return [
                'form' => [
                    'action' => '/register',
                    'method' => 'POST',
                    'class' => 'max-w-sm mx-auto',
                    'id' => 'registrationForm',
                    'inputs' => [
                        [
                            'type' => 'email',
                            'name' => 'email',
                            'label' => 'email',
                            'class' => 'block w-full p-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-base focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                            'placeholder' => 'test'
                        ],
                        [
                            'type' => 'password',
                            'name' => 'password',
                            'label' => 'password',
                            'class' => 'form-control'
                        ],
                        [
                            'type' => 'password',
                            'name' => 'confirm_password',
                            'label' => 'password',
                            'class' => 'form-control'
                        ],
                    ],
                    'buttons' => [
                        [
                            'type' => 'submit',
                            'label' => 'Submit',
                            'class' => 'text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700'
                        ],
                    ]
                ]
            ];
        }
    }