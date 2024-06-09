<?php

namespace QueryBuilder\clauses;

class SelectClauses
{
    private array $actualSelectArr = [];
    public function __construct(array &$actualSelectArr)
    {
        $this->actualSelectArr = &$actualSelectArr;        
    }
    public function select( array $columns)
    {
        return array_merge($this->actualSelectArr, $columns);
    }
    public function selectCount(array $columns = NULL) 
    {
        return " COUNT( " . isset($columns) ? implode(",", $columns) : "*" . ") ";
    }
}