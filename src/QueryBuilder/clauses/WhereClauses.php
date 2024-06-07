<?php
namespace QueryBuilder\clauses;

use QueryBuilder\bootstrap\BaseQuery;
use QueryBuilder\helpers\W5IQueryBuilderHelpers;

class WhereClauses
{
    use W5IQueryBuilderHelpers;
    private array $bindValues = [];
    private array $placeholderValues = [];
    
    public function __construct (array &$bindValues, array &$placeholderValues) 
    {  
        $this->bindValues = &$bindValues;
        $this->placeholderValues = &$placeholderValues;
    }
    public function whereBetween($column, $start, $end )
    {
        //binded column : serve para realizar a remocao de um possivel alias
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= ["$bind._start" => $start];
        $this->bindValues[]= ["$bind._end" => $end];
       
        return " WHERE $column BETWEEN :$column._start AND :$bind._end ";
    }
    public function whereIn(string $column, array $items) 
    {
        $placeholders = implode(",", array_fill(0, count($items), "?"));

        $this->placeholderValues = array_merge($this->placeholderValues, $items );
        
        return " WHERE $column IN ( $placeholders ) ";
    }
    public function andWhereIn(string $column, array $items) 
    {
        $placeholders = implode(",", array_fill(0, count($items), "?"));

        $this->placeholderValues = array_merge($this->placeholderValues, $items );
        
        return " AND $column IN ( $placeholders ) ";
    }
    public function whereNotIn(string $column, array $items) 
    {
        //literalmente construindo uma string com placeholders de arcordo com  aquantudade q=e  placeholders
        $placeholders = implode(",", array_fill(0, count($items), "?"));

        $this->placeholderValues = array_merge($this->placeholderValues, $items );
        
        return " WHERE $column NOT IN ( $placeholders ) ";
    }
    public function andWhere(string $column, string $operator, string|int $value)  
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        return " AND " .$column. " ".$operator. " ". " :$bind ";
    }
    public function orWhere(string $column, string $operator, string|int $value) 
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        return " OR ". " $column " .$operator. " ". " :$bind ";
    }
    public function where(string $column, string $operator, string $value) 
    {

        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        return " WHERE ".$column." ".$operator. " " . ":$bind" ." ";
    }
    public function andWhereLike (string $column, string|int $value) 
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];
        
        return " AND ".$column." LIKE " . " :$bind ";

    }
    public function andWhereILike (string $column, string|int $value) 
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        return " AND ".$column." ILIKE " . " :$bind ";

    }

    public function orWhereLike (string $column, string|int $value) 
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        return " OR ".$column." LIKE " . " :$bind ";

    }
    public function orWhereILike (string $column, string $value) 
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        return " OR ".$column." ILIKE " . " :$bind " ;

    }

   
}