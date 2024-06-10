<?php
namespace QueryBuilder\core;
use Adianti\Database\TTransaction;
use Exception;
use PDO;
use PDOException;
use QueryBuilder\config\DataBaseSettings;
use QueryBuilder\helpers\W5IQueryBuilderHelpers;
use QueryBuilder\config\DbConnection;
abstract class W5IQueryBuilderCore 
{
    use W5IQueryBuilderHelpers;
    private bool $isAdiantiFrameworkProject ;
    protected array $placeholderValues = [];
    protected array $bindValues = [] ; 
    protected array $placeholders = [] ;
    protected string $tableName;
    
    /**
     * @summary : carrega finalmente a query montada anteriormente, podendo ser tanto num ambiente com adianti ou sem...
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @created : 07/06/2024
     * @param string $query : query que é passada no metodo abstrato W5iQueryBuilder::first()
     * @param bool $isValuable : identifica se o metodo irá retornar apenas o valor (string|int|bool) desacoplado de um objeto 
     * @return array<object>|void|mixed: 
     */
    public function fetchObject(string $query, bool $isValuable = false) 
    {
        $instance = DataBaseSettings::getInstance();
        
        $this->isAdiantiFrameworkProject = $instance->getDataFromArrayByKey("isAdiantiFrameworkProject");
        $config = $instance->getSettings();
        try 
        {
            if ($this->isAdiantiFrameworkProject) 
            {
                // Assume que é um projeto Adianti Framework
                TTransaction::open($config["dbName"]);
                $pdo = TTransaction::get();
            } 
            else 
            {
                // Caso contrário, use DbConnectionService
                $conn = new DbConnection();
                $conn->PDO->beginTransaction();
                $pdo = $conn->PDO;
            }
            $stmt = $pdo->prepare($query);
            if(isset($this->bindValues) && is_array($this->bindValues) && !empty($this->bindValues)) 
            {
                foreach($this->bindValues as $bind) 
                {   
                    $key = array_key_first($bind);
                    $bindValue = $bind[$key];
                    
                    switch (gettype($bindValue)) 
                    {
                        case "boolean":
                            $paramType = PDO::PARAM_BOOL;
                            break;
                        case "string":
                            $paramType = PDO::PARAM_STR;
                            break;
                        case "integer":
                            $paramType = PDO::PARAM_INT;
                            break;
                        default:
                            $paramType = PDO::PARAM_STR;
                    }

                    $stmt->bindValue(":$key", $bindValue, $paramType);
                }
            }
            $stmt->execute(!empty($this->placeholderValues)? $this->placeholderValues : NULL);
            $object = $stmt->fetchObject();

            if($isValuable) 
            {
                $arr = (array) $object;
                $key = array_key_first($arr);
                return $arr[$key];
            }

            return $object;
        } 
        catch (PDOException $e) 
        {
            $this->isAdiantiFrameworkProject ? TTransaction::rollBack() : $conn->PDO->rollBack();
            throw new Exception($e->getMessage());
        } 
        finally 
        {
            $this->isAdiantiFrameworkProject ? TTransaction::close() : $conn->PDO->commit();
        }
        
    }
    
    /**
     * @summary : carrega finalmente a query montada anteriormente, podendo ser tanto num ambiente com adianti ou sem...
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @created : 07/06/2024
     * @return array<object>|void: 
     */
    public function fetchAll(string $query, $isValueable = false) 
    {
        $instance = DataBaseSettings::getInstance();
        $config   = $instance->getSettings();

        $this->isAdiantiFrameworkProject = $config["isAdiantiFrameworkProject"];

        try 
        {
            if ($this->isAdiantiFrameworkProject) 
            {
                // Assume que é um projeto Adianti Framework
                TTransaction::open($config["dbName"]);
                $pdo = TTransaction::get();
            } 
            else 
            {
                // Caso contrário, use DbConnectionService
                $conn = new DbConnection();
                $conn->PDO->beginTransaction();
                $pdo = $conn->PDO;
            }

            $stmt = $pdo->prepare($query);
            if(isset($this->bindValues) && is_array($this->bindValues) && !empty($this->bindValues)) 
            {
                foreach($this->bindValues as $bind) 
                {   
                    $key = array_key_first($bind);
                    $bindValue = $bind[$key];
                    
                    switch (gettype($bindValue)) 
                    {
                        case "boolean":
                            $paramType = PDO::PARAM_BOOL;
                            break;
                        case "string":
                            $paramType = PDO::PARAM_STR;
                            break;
                        case "integer":
                            $paramType = PDO::PARAM_INT;
                            break;
                        default:
                            $paramType = PDO::PARAM_STR;
                    }

                    $stmt->bindValue(":$key", $bindValue, $paramType);
                }
            }
            $stmt->execute(!empty($this->placeholderValues) ? $this->placeholderValues : NULL);

            if($isValueable) 
            {
                $associated = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $arrWithOnlyValues = $this->getArrValues($associated);
               
                return $arrWithOnlyValues;
            }
            $objects = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $objects;
        }
        catch (PDOException $e) 
        {
            $this->isAdiantiFrameworkProject ? TTransaction::rollBack() : $conn->PDO->rollBack();
            throw new Exception($e->getMessage());
        } 
        finally 
        {
            $this->isAdiantiFrameworkProject ? TTransaction::close() : $conn->PDO->commit();
        }
    }
   
}