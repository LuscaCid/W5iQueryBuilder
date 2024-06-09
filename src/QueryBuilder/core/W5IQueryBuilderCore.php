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
     * @summary  metodo simples apenas para "achatar um array" 
     * @param array $array : array que vai ser achatado
     * */
    public function fetchObject(string $query) 
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
                    $stmt->bindValue(":$key", $bindValue, is_numeric($bindValue) ? PDO::PARAM_INT : PDO::PARAM_STR);
                }
            }
            $stmt->execute(!empty($this->placeholderValues)? $this->placeholderValues : NULL);
            $object = $stmt->fetchObject();

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
            //TTransaction::open($this->transactionUnit);
            //$pdo = TTransaction::get();

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
                    $stmt->bindValue(":$key", $bindValue, is_numeric($bindValue) ? PDO::PARAM_INT : PDO::PARAM_STR);
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