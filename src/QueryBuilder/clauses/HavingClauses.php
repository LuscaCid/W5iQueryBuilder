<?php
namespace QueryBuilder\clauses;
use QueryBuilder\bootstrap\BaseQuery;

class HavingClauses extends BaseQuery
{
    public function having(string $column, string $operator, string $value)
    {
        $bind = $this->cutBindColumn($column);
        
        $this->bindValues[]= [$bind => $value];

        $this->having[] = " $column $operator :$bind";
        return $this;

    }
    public function havingIn(string $column, array $items) 
    {
        $placeholders = implode(",", array_fill(0, count($items), "?"));

        $this->placeholderValues = array_merge($this->placeholderValues, $items );

        $this->having[] = " HAVING $column IN ( $placeholders ) ";

        return $this;
    }
}