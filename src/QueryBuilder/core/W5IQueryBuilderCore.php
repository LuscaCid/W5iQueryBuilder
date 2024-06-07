<?php
namespace QueryBuilder\core;
use Adianti\Database\TTransaction;
use Exception;
use PDO;
use PDOException;
use QueryBuilder\helpers\W5IQueryBuilderHelpers;
use QueryBuilder\config\DbConnection;
abstract class W5IQueryBuilderCore 
{
    use W5IQueryBuilderHelpers;
    private bool $isAdiantiFrameworkProject ;
    protected array $placeholderValues = [];
    protected array $bindValues = [] ; 
    protected string $tableName;
    protected string $transactionUnit;
    
    /**
     * @summary  metodo simples apenas para "achatar um array" 
     * @param array $array : array que vai ser achatado
     * */
    public function fetchObject(string $query) 
    {
        include "../config/DataSource.php";
        $this->isAdiantiFrameworkProject = $config['$isAdiantiFrameworkProject'];
        try 
        {
            // TTransaction::open($this->transactionUnit);
            // $pdo = TTransaction::get();
            $instance = new DbConnection();
            $pdo = $instance->PDO;
            
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
            $objects = $stmt->fetchObject(PDO::FETCH_OBJ);
            
            return $objects;
        } catch ( PDOException $e) 
        {
            //TTransaction::rollback();
            throw new Exception($e->getMessage());
        }
        finally 
        {
            //TTransaction::close();
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
        include "../config/DataSource.php";
        $this->isAdiantiFrameworkProject = false;
        try 
        {
            //TTransaction::open($this->transactionUnit);
            //$pdo = TTransaction::get();

            if ($this->isAdiantiFrameworkProject) 
            {
                // Assume que é um projeto Adianti Framework
                TTransaction::open();
                $conn = TTransaction::get();
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
            // Rollback transaction in case of error
            if ($this->isAdiantiFrameworkProject) 
            {
                TTransaction::rollback();
            } 
            else 
            {
                $conn->PDO->rollBack();
            }
    
            throw new Exception($e->getMessage());
        } 
        finally 
        {
            // Close transaction if it's Adianti Framework
            if ($this->isAdiantiFrameworkProject) 
            {
                TTransaction::close();
            }
            else 
            {
                $conn->PDO->commit();
            }
        }
    }
   
}