<?php
namespace QueryBuilder\clauses;

use QueryBuilder\bootstrap\BaseQuery;

class WhereClauses extends BaseQuery
{
    public function whereBetween($column, $start, $end)
    {
        //binded column : serve para realizar a remocao de um possivel alias
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= ["$bind._start" => $start];
        $this->bindValues[]= ["$bind._end" => $end];
       
        $this->where[]= " $column BETWEEN :$column._start AND :$bind._end ";
        return $this;
    }
    public function whereIn(string $column, array $items) 
    {
        $placeholders = implode(",", array_fill(0, count($items), "?"));

        $this->placeholderValues = array_merge($this->placeholderValues, $items );
        $this->where[] = " $column IN ( $placeholders ) ";

        return $this;
    }
    public function andWhereIn(string $column, array $items) 
    {
        $placeholders = implode(",", array_fill(0, count($items), "?"));

        $this->placeholderValues = array_merge($this->placeholderValues, $items );
        $this->where[] = " AND $column IN ( $placeholders ) ";

        return $this;
    }
    public function whereNotIn(string $column, array $items) 
    {
        //literalmente construindo uma string com placeholders de arcordo com  aquantudade q=e  placeholders
        $placeholders = implode(",", array_fill(0, count($items), "?"));

        $this->placeholderValues = array_merge($this->placeholderValues, $items );
        $this->where[] = " $column NOT IN ( $placeholders ) ";

        return $this;
        
    }
    public function andWhere(string $column, string $operator, string|int $value)  
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        $this->where[]= " AND " .$column. " ".$operator. " ". " :$bind ";
        return $this;
    }
    public function orWhere(string $column, string $operator, string|int $value) 
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        $this->where[]= " OR ". " $column " .$operator. " ". " :$bind ";
        return $this;
    }
    public function where(string $column, string $operator, string $value) 
    {

        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        $this->where[]= " WHERE ".$column." ".$operator. " " . ":$bind" ." ";
        return $this;
    }
    public function andWhereLike (string $column, string|int $value) 
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];
        
        $this->where[]= " AND ".$column." LIKE " . " :$bind ";

        return $this;
    }
    public function andWhereILike (string $column, string|int $value) 
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        $this->where[]= " AND ".$column." ILIKE " . " :$bind ";

        return $this;
    }

    public function orWhereLike (string $column, string|int $value) 
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        $this->where[]= " OR ".$column." LIKE " . " :$bind ";

        return $this;
    }
    public function orWhereILike (string $column, string $value) 
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        $this->where[]= " OR ".$column." ILIKE " . " :$bind " ;

        return $this;
    }

   
}