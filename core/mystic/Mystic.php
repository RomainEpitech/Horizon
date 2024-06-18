<?php

    namespace Horizon\Core\Mystic;

    use Horizon\Core\Database\Database;
    use Exception;
    use PDO;
    use PDOException;
    use ReflectionClass;

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
    }