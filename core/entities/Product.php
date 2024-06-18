<?php

    namespace Horizon\Core\Entities;

    use Horizon\Core\Mystic\Mystic;

    class Product extends Mystic {
        protected static $tableName = 'Product';

        public $id;

        public $price;

        public $name;

        public function getId() {
            return $this->id;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function getPrice() {
            return $this->price;
        }

        public function setPrice($price) {
            $this->price = $price;
        }

        public function getName() {
            return $this->name;
        }

        public function setName($name) {
            $this->name = $name;
        }
    }
