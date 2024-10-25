<?php

    namespace Horizon\Core\Mystic\Queries;

    use PDO;
    use Exception;

    class Table {
        protected $table;
        protected $where = [];
        protected $orderBy = [];
        protected $limit;
        protected $offset;
        protected $pdo;
        protected $className;
        
        public function __construct(PDO $pdo, string $table, string $className) {
            $this->pdo = $pdo;
            $this->table = $table;
            $this->className = $className;
        }
        
        public function where(array $conditions): self {
            foreach ($conditions as $column => $value) {
                $this->where[$column] = $value;
            }
            return $this;
        }
        
        public function orderBy(array $orders): self {
            foreach ($orders as $column => $direction) {
                $this->orderBy[$column] = strtoupper($direction);
            }
            return $this;
        }
        
        public function limit(int $limit): self {
            $this->limit = $limit;
            return $this;
        }
        
        public function offset(int $offset): self {
            $this->offset = $offset;
            return $this;
        }
        
        protected function buildQuery(): array {
            $query = "SELECT * FROM {$this->table}";
            $params = [];
            
            if (!empty($this->where)) {
                $whereConditions = [];
                foreach ($this->where as $column => $value) {
                    $whereConditions[] = "{$column} = :where_{$column}";
                    $params["where_{$column}"] = $value;
                }
                $query .= " WHERE " . implode(' AND ', $whereConditions);
            }
            
            if (!empty($this->orderBy)) {
                $orderClauses = [];
                foreach ($this->orderBy as $column => $direction) {
                    $orderClauses[] = "{$column} {$direction}";
                }
                $query .= " ORDER BY " . implode(', ', $orderClauses);
            }
            
            if ($this->limit !== null) {
                $query .= " LIMIT :limit";
                $params['limit'] = (int) $this->limit;
            }
            
            if ($this->offset !== null) {
                $query .= " OFFSET :offset";
                $params['offset'] = (int) $this->offset;
            }
            
            return [$query, $params];
        }
        
        protected function execute(): array {
            [$query, $params] = $this->buildQuery();
            $stmt = $this->pdo->prepare($query);
            
            foreach ($params as $key => $value) {
                $type = PDO::PARAM_STR;
                if (is_int($value)) {
                    $type = PDO::PARAM_INT;
                } elseif (is_bool($value)) {
                    $type = PDO::PARAM_BOOL;
                } elseif (is_null($value)) {
                    $type = PDO::PARAM_NULL;
                }
                $stmt->bindValue(":{$key}", $value, $type);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get(): string {
            try {
                $results = $this->execute();
                
                array_walk_recursive($results, function(&$value, $key) {
                    if ($key === 'created_at' && $value !== null) {
                        $value = (new \DateTime($value))->format('Y-m-d H:i:s');
                    }
                    if ($key === 'role' && is_string($value)) {
                        $value = json_decode($value, true);
                    }
                });
                
                return json_encode($results, JSON_THROW_ON_ERROR);
            } catch (Exception $e) {
                throw new Exception("Error getting JSON results: " . $e->getMessage());
            }
        }

        public function first(): string {
            $this->limit = 1;
            $results = $this->execute();
            
            if (empty($results)) {
                return json_encode(null);
            }

            $result = $results[0];
            foreach ($result as $key => &$value) {
                if ($key === 'created_at' && $value !== null) {
                    $value = (new \DateTime($value))->format('Y-m-d H:i:s');
                }
                if ($key === 'role' && is_string($value)) {
                    $value = json_decode($value, true);
                }
            }
            
            return json_encode($result, JSON_THROW_ON_ERROR);
        }
    }