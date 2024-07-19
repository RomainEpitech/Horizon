<?php

    namespace Horizon\Core\Entities;

    use Horizon\Core\Mystic\Mystic;

    class Migrations extends Mystic {
        protected static $tableName = 'Migrations';
        public $id;

        public $migration;

        public $executed_at;

        public function getId() {
            return $this->id;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function getMigration() {
            return $this->migration;
        }

        public function setMigration($migration) {
            $this->migration = $migration;
        }

        public function getExecuted_at() {
            return $this->executed_at;
        }

        public function setExecuted_at($executed_at) {
            $this->executed_at = $executed_at;
        }
    }
