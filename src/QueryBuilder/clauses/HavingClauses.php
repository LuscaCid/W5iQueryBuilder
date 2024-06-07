<?php
namespace QueryBuilder\clauses;
use QueryBuilder\bootstrap\BaseQuery;
use QueryBuilder\helpers\W5IQueryBuilderHelpers;

class HavingClauses
{
    use W5IQueryBuilderHelpers;

    private array $bindValues;
    private array $placeholderValues;
    public function __construct (array &$bindValues, array &$placeholderValues)
    {
        $this->bindValues = &$bindValues;
        $this->placeholderValues = &$placeholderValues;
    }
    public function having(string $column, string $operator, string $value)
    {
        $bind = $this->cutBindColumn($column);
        
        $this->bindValues[]= [$bind => $value];

        return " $column $operator :$bind";

    }
    public function havingIn(string $column, array $items) 
    {
        $placeholders = implode(",", array_fill(0, count($items), "?"));

        $this->placeholderValues = array_merge($this->placeholderValues, $items );

        return " $column IN ( $placeholders ) ";
    }
}