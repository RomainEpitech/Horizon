<?php

    namespace Horizon\Core\Mystic;

    use Horizon\Core\Database\Database;
    use Exception;
    use PDO;
    use PDOException;
    use ReflectionClass;
    use ReflectionProperty;

    class Mystic extends Database {
        public static function fetchAll($entityClass) {
            $db = self::getInstance()->getConn();
            
            $reflection = new ReflectionClass($entityClass);
            $tableName = $reflection->getStaticPropertyValue('tableName');
    
            $sql = "SELECT * FROM $tableName";
            $stmt = $db->prepare($sql);
            
            try {
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_CLASS, $entityClass);
            } catch (PDOException $e) {
                throw new Exception("SQL execution error: " . $e->getMessage());
            }
        }

        public static function fetchOneBy($entityClass, $conditions) {
            $db = self::getInstance()->getConn();
            
            $reflection = new ReflectionClass($entityClass);
            $tableName = $reflection->getStaticPropertyValue('tableName');
    
            $conditionString = implode(' AND ', array_map(function($key) {
                return "$key = :$key";
            }, array_keys($conditions)));
    
            $sql = "SELECT * FROM $tableName WHERE $conditionString LIMIT 1";
            $stmt = $db->prepare($sql);
    
            try {
                $stmt->execute($conditions);
                return $stmt->fetchObject($entityClass);
            } catch (PDOException $e) {
                throw new Exception("SQL execution error: " . $e->getMessage());
            }
        }

        public static function insert($entityClass, $values) {
            $db = self::getInstance()->getConn();
    
            $reflection = new ReflectionClass($entityClass);
            $tableName = $reflection->getStaticPropertyValue('tableName');
    
            $entity = new $entityClass();
    
            foreach ($values as $key => $value) {
                if ($reflection->hasProperty($key)) {
                    $property = $reflection->getProperty($key);
                    $property->setAccessible(true);
                    $property->setValue($entity, $value);
                }
            }
    
            $properties = array_map(function (ReflectionProperty $property) use ($entity) {
                return $property->getName();
            }, $reflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED));
    
            $columns = implode(", ", array_keys($values));
            $placeholders = ":" . implode(", :", array_keys($values));
            $sql = "INSERT INTO " . $tableName . " (" . $columns . ") VALUES (" . $placeholders . ")";
            $stmt = $db->prepare($sql);
    
            foreach ($values as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
    
            try {
                $stmt->execute();
    
                $id = $db->lastInsertId();
                $entity->id = $id;
    
                foreach ($properties as $property) {
                    if (!array_key_exists($property, $values)) {
                        $propertyInstance = $reflection->getProperty($property);
                        $propertyInstance->setAccessible(true);
                        $propertyInstance->setValue($entity, null);
                    }
                }
    
                return $entity;
            } catch (PDOException $e) {
                throw new Exception("SQL execution error: " . $e->getMessage());
            }
        }
    }