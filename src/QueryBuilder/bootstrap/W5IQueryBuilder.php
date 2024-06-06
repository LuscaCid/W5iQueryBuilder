<?php
namespace QueryBuilder\bootstrap;


use Adianti\Database\TRecord;
use Adianti\Database\TTransaction;
use Exception;
use QueryBuilder\wheres\W5IQuerybBuilderWhereClauses;

/**
 * @summary : Um simples query builder para padronizar montagem de queries, deixando mais organizado, codigo limpo, para ter consultas sendo feitas de forma mais padronizada
 * @author Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
 * @created 04/06/2024
 */
class W5IQueryBuilder extends W5IQuerybBuilderWhereClauses
{

    public function __construct(string $tableName, string $transactionUnit)
    {
        $this->tableName = $tableName;
        $this->transactionUnit = $transactionUnit;
    }
    public function __call($name, $arguments)
    {
        switch (strtoupper($name)) 
        {
            case "JOIN":
                $clauseWhereInsideId = $this->verifyIfAlreadyHasClauseWhere($this->query);

                $clauseWhereInsideId && throw new Exception("A cláusula WHERE já esta presente na string, para adicionar um join, faça a clausula where vir após.");
                
                $this->query .= " JOIN $arguments[0] ".$arguments[1]." ON ".$arguments[2];
                
                break;
            
        }
    }
    
    public function getQuery() 
    {
        return $this->query;
    }
    public function selectCount() 
    {
        $this->query = "SELECT COUNT(*) FROM ".$this->tableName;
        return $this;
    }
    public function select(array $columns) 
    {
        $this->query = "SELECT ".implode(",", $columns)." FROM ".$this->tableName;
        return $this;
    }
   
    public function orderBy(string $column, string $order) 
    {
        $this->query.= " ORDER BY ".$column." ".$order;
        return $this;
    }
    public function offset(int $offset = 0)
    {
        $this->bindValues[]= ["offset" => $offset];

        if(str_contains($this->query, "OFFSET")) 
        {
            throw new Exception("Cláusula offset ja passada.");
        }
        $this->query.= " OFFSET :offset" ;
        return $this;
    }
    public function limit(int $limit = 5) 
    {
        $this->bindValues[]= ["limit" => $limit];

        if(str_contains($this->query, "LIMIT")) 
        {
            throw new Exception("Cláusula limit ja passada.");
        }
        $this->query.= " LIMIT :limit" ;
        return $this;
    }
 

    /**
     * @param mixed $column : podendo ser o nome da tasele
     */
    public function innerJoin (mixed $table, string $leftSide, string $operator, string $rightSide ) 
    {
        $clauseWhereInsideId = $this->verifyIfAlreadyHasClauseWhere($this->query);

        $clauseWhereInsideId && throw new Exception("A cláusula WHERE já esta presente na string, para adicionar um join, faça a clausula where vir após.");

        $this->query.= " INNER JOIN ".$table." ON ".$leftSide." ". $operator. " ". $rightSide ;
        return $this;
    }
    public function leftJoin (string $table, string $leftSide, string $operator, string $rightSide ) 
    {
        $clauseWhereInsideId = $this->verifyIfAlreadyHasClauseWhere($this->query);

        $clauseWhereInsideId && throw new Exception("A cláusula WHERE já esta presente na string, para adicionar um join, faça a clausula where vir após.");

        $this->query.= "LEFT JOIN ".$table." ON ".$leftSide." ". $operator. " ". $rightSide ;
        return $this;
    }

    /**
     * @summary : metodo para realizar where in mas podendo passsar ou o array de 
     * @author Lucas Cid
     * @param string $column : coluna para ser comparada com o valor que for inserido dentro do array ou sql que vai ser executado retornado o 
     */
    public function whereIn(string $column, array $items) 
    {
        //literalmente construindo uma string com placeholders de arcordo com  aquantudade q=e  placeholders
        $placeholders = implode(",", array_fill(0, count($items), "?"));

        $this->placeholderValues = array_merge($this->placeholderValues, $items );
        $this->query .= " WHERE $column IN ( $placeholders ) ";

        return $this;
        
    }
    

    /**
     * @summary : right join execute with possibility of pass inside the first argument the model
     */
    public function rightJoin (mixed $table, string $leftSide, string $operator, string $rightSide ) 
    {
        $clauseWhereInsideId = $this->verifyIfAlreadyHasClauseWhere($this->query);

        $clauseWhereInsideId && throw new Exception("A cláusula WHERE já esta presente na string, para adicionar um join, faça a clausula where vir após.");

        ////////////////////////////////////////////////////////////////
        // if($table instanceof TRecord) 
        // {
        //     $table = property_exists($table, "TABLENAME") ? $table->TABLENAME : throw new Exception("propriedade TABLENAME nao existe no model.");
        // } 
        // else 
        // {
        //     $this->query.= " RIGHT JOIN ".$table." ON ".$leftSide." ". $operator. " ". $rightSide ;
        // }
        
        return $this;
    }
   
}
