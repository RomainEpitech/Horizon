<?php

    namespace Horizon\Core\Commands\Migrations;

    class ColumnDefinition {
        protected $type;
        protected $name;
        protected $length;
        protected $nullable = false;
        protected $unsigned = false;
        protected $autoIncrement = false;
        protected $primary = false;
        protected $unique = false;
        protected $default = null;
        protected $after = null;
        protected $first = false;
    
        public function __construct(string $type, string $name, ?int $length = null) {
            $this->type = $type;
            $this->name = $name;
            $this->length = $length;
        }
    
        public function nullable(): self {
            $this->nullable = true;
            return $this;
        }
    
        public function unsigned(): self {
            $this->unsigned = true;
            return $this;
        }
    
        public function unique(): self {
            $this->unique = true;
            return $this;
        }
    
        public function autoIncrement(): self {
            $this->autoIncrement = true;
            return $this;
        }
    
        public function primary(): self {
            $this->primary = true;
            return $this;
        }
    
        public function default($value): self {
            $this->default = $value;
            return $this;
        }
    
        public function after(string $column): self {
            $this->after = $column;
            return $this;
        }
    
        public function first(): self {
            $this->first = true;
            return $this;
        }
    
        public function toSql(): string {
            $sql = "`{$this->name}` " . strtoupper($this->type);
            
            if ($this->length !== null) {
                $sql .= "({$this->length})";
            }
            
            if ($this->unsigned) {
                $sql .= ' UNSIGNED';
            }
            
            if ($this->nullable) {
                $sql .= ' NULL';
            } else {
                $sql .= ' NOT NULL';
            }
            
            if ($this->autoIncrement) {
                $sql .= ' AUTO_INCREMENT';
            }
            
            if ($this->default !== null) {
                $sql .= ' DEFAULT ' . (is_string($this->default) ? "'$this->default'" : $this->default);
            }
            
            if ($this->unique) {
                $sql .= ' UNIQUE';
            }
            
            if ($this->primary) {
                $sql .= ' PRIMARY KEY';
            }
            
            if ($this->after) {
                $sql .= " AFTER `{$this->after}`";
            }
            
            if ($this->first) {
                $sql .= " FIRST";
            }
            
            return $sql;
        }
    }