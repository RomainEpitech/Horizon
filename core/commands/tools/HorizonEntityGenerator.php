<?php

namespace Horizon\Core\Commands\Tools;

use Horizon\Core\Database\Database;
use Horizon\Core\Env\EnvLoader;
use PDO;
use Horizon\Core\Inc\Error;
use Horizon\Core\Inc\Success;

class HorizonEntityGenerator 
{
    private $pdo;
    private $namespace = "Horizon\\App\\Models";
    private $excludedTables = ['migrations'];
    private $customTypes = [
        'email' => 'string',
        'password' => 'string',
        'created_at' => '\DateTime',
        'updated_at' => '\DateTime',
        'deleted_at' => '\DateTime',
        'role' => 'array'
    ];
    
    public function __construct()
    {
        $envLoader = new EnvLoader();
        $envLoader->load();
        $this->pdo = Database::run()->getConn();
    }

    public function generate()
    {
        try {
            $tables = $this->getTables();
            $generatedCount = 0;

            foreach ($tables as $table) {
                if (!in_array($table, $this->excludedTables)) {
                    $this->generateEntityForTable($table);
                    $generatedCount++;
                }
            }

            if ($generatedCount > 0) {
                Success::displaySuccessMessage("Generated $generatedCount model" . ($generatedCount > 1 ? 's' : '') . " successfully!");
            } else {
                Error::displayErrorMessage("No tables found to generate models from.");
            }
        } catch (\Exception $e) {
            Error::displayErrorMessage("Error generating entities: " . $e->getMessage());
        }
    }

    private function generateEntityForTable($tableName)
    {
        $columns = $this->getTableColumns($tableName);
        $className = $this->formatClassName($tableName);
        $content = $this->generateEntityContent($className, $columns, $tableName);
        
        $dir = "./App/Models";
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        
        $filePath = "$dir/$className.php";
        file_put_contents($filePath, $content);
    }

    private function getTables()
    {
        $stmt = $this->pdo->query("SHOW TABLES");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private function getTableColumns($tableName)
    {
        $stmt = $this->pdo->query("DESCRIBE $tableName");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function formatClassName($tableName)
    {
        $singular = rtrim($tableName, 's');
        return str_replace('_', '', ucwords($singular, '_'));
    }

    private function generateEntityContent($className, $columns, $tableName)
    {
        $properties = [];
        $fillable = [];
        $gettersSetters = [];
        $primaryKey = null;

        foreach ($columns as $column) {
            $columnName = $column['Field'];
            $type = isset($this->customTypes[$columnName]) 
                   ? $this->customTypes[$columnName] 
                   : $this->getPhpTypeFromMysql($column['Type']);
            
            // Add property with type comment
            $properties[] = "    /** @var $type */";
            $properties[] = "    private \$$columnName;\n";
            
            // Add to fillable if not primary key
            if ($column['Key'] !== 'PRI') {
                $fillable[] = "'$columnName'";
            } else {
                $primaryKey = $columnName;
            }
            
            // Generate getter and setter
            $methodName = ucfirst($columnName);
            $gettersSetters[] = $this->generateGetter($columnName, $methodName, $type);
            $gettersSetters[] = $this->generateSetter($columnName, $methodName, $type);
        }

        $fillableStr = implode(", ", $fillable);
        $propertiesStr = implode("\n", $properties);
        $gettersSettersStr = implode("\n\n", $gettersSetters);

        return "<?php

namespace {$this->namespace};

/**
 * Class $className
 * @package {$this->namespace}
 * 
 * Generated from table: $tableName
 */
class $className
{
    /**
     * The table associated with the model
     * @var string
     */
    private \$table = '$tableName';

    /**
     * The attributes that are mass assignable
     * @var array
     */
    private \$fillable = [$fillableStr];

    /**
     * The primary key for the model
     * @var string
     */
    private \$primaryKey = '$primaryKey';

$propertiesStr

$gettersSettersStr
}";
    }

    private function generateGetter($property, $methodName, $type)
    {
        return "    /**
     * Get the value of $property
     * @return $type
     */
    public function get$methodName(): $type
    {
        return \$this->$property;
    }";
    }

    private function generateSetter($property, $methodName, $type)
    {
        return "    /**
     * Set the value of $property
     * @param $type \$$property
     * @return self
     */
    public function set$methodName($type \$$property): self
    {
        \$this->$property = \$$property;
        return \$this;
    }";
    }

    private function getPhpTypeFromMysql($mysqlType)
    {
        if (strpos($mysqlType, 'int') !== false) return 'int';
        if (strpos($mysqlType, 'decimal') !== false || 
            strpos($mysqlType, 'float') !== false || 
            strpos($mysqlType, 'double') !== false) return 'float';
        if (strpos($mysqlType, 'datetime') !== false ||
            strpos($mysqlType, 'timestamp') !== false) return '\DateTime';
        if (strpos($mysqlType, 'tinyint(1)') !== false) return 'bool';
        if (strpos($mysqlType, 'json') !== false) return 'array';
        return 'string';
    }
}