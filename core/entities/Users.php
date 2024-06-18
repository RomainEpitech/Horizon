<?php

    namespace Horizon\Core\Entities;

    use Horizon\Core\Mystic\Mystic;

    class Users extends Mystic {
        protected static $tableName = 'Users';

        private $id;

        private $email;

        private $password;

        private $created_at;

        private $token;

        private $role;

        public function getId() {
            return $this->id;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function getEmail() {
            return $this->email;
        }

        public function setEmail($email) {
            $this->email = $email;
        }

        public function getPassword() {
            return $this->password;
        }

        public function setPassword($password) {
            $this->password = $password;
        }

        public function getCreated_at() {
            return $this->created_at;
        }

        public function setCreated_at($created_at) {
            $this->created_at = $created_at;
        }

        public function getToken() {
            return $this->token;
        }

        public function setToken($token) {
            $this->token = $token;
        }

        public function getRole() {
            return $this->role;
        }

        public function setRole($role) {
            $this->role = $role;
        }
    }
