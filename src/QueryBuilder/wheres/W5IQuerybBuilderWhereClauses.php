<?php
namespace QueryBuilder\wheres;

use Exception;
use QueryBuilder\core\W5IQueryBuilderCore;

abstract class W5IQuerybBuilderWhereClauses extends W5IQueryBuilderCore
{

    public function andWhereIn(string $column, array $items) 
    {
        $placeholders = implode(",", array_fill(0, count($items), "?"));

        $this->placeholderValues = array_merge($this->placeholderValues, $items );
        $this->query .= " AND $column IN ( $placeholders ) ";

        return $this;
    }
    public function whereNotIn(string $column, array $items) 
    {
        //literalmente construindo uma string com placeholders de arcordo com  aquantudade q=e  placeholders
        $placeholders = implode(",", array_fill(0, count($items), "?"));

        $this->placeholderValues = array_merge($this->placeholderValues, $items );
        $this->query .= " WHERE $column NOT IN ( $placeholders ) ";

        return $this;
        
    }
    public function andWhere(string $column, string $operator, string $value)  
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        $this->query.= " AND " .$column. " ".$operator. " ". " :$bind ";
        return $this;
    }
    public function orWhere(string $column, string $operator, string $value) 
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        $this->query.= " OR ". " $column " .$operator. " ". " :$bind ";
        return $this;
    }
    public function where(string $column, string $operator, string $value) 
    {
        $this->verifyIfAlreadyHasClauseWhere($this->query) && throw new Exception("A cláusula WHERE já esta presente na string, substitua por um andWhere ou orWhere, etc.");

        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        $this->query.= " WHERE ".$column." ".$operator. " " . ":$bind" ." ";
        return $this;
    }
    public function andWhereLike (string $column, $value) 
    {
        $this->bindValues[]= [$column => $value];
        $this->query .= $this->verifyIfAlreadyHasClauseWhere($this->query) ? " AND ".$column." LIKE " . " :$column " : " WHERE ".$column." LIKE " . " :$column " ;

        return $this;
    }
    public function andWhereILike (string $column, string $value) 
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        $this->query .= $this->verifyIfAlreadyHasClauseWhere($this->query) ? " AND ".$column." ILIKE " . " :$column " : " WHERE ".$column." ILIKE " . " :$bind " ;

        return $this;
    }

    public function orWhereLike (string $column, $value) 
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        $this->query .= $this->verifyIfAlreadyHasClauseWhere($this->query) ? " OR ".$column." LIKE " . " :$column " : " WHERE ".$column." LIKE " . " :$bind " ;

        return $this;
    }
    public function orWhereILike (string $column, string $value) 
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        $this->query .= $this->verifyIfAlreadyHasClauseWhere($this->query) ? " OR ".$column." ILIKE " . " :$column " : " WHERE ".$column." ILIKE " . " :$bind " ;

        return $this;
    }
}