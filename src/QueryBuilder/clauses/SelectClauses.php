<?php

namespace QueryBuilder\clauses;

class SelectClauses
{
    private array|NULL $actualSelectArr = [];
    public function __construct(array|NULL &$actualSelectArr)
    {
        $this->actualSelectArr = &$actualSelectArr;        
    }
    public function select(array $columns)
    {
        $this->actualSelectArr = [];
        return $this->actualSelectArr[] = array_merge($this->actualSelectArr, $columns);
    }
    public function selectCount(array $columns = NULL, $alias = NULL) 
    {
        $columnsImploded = is_array($columns) ? implode(",", $columns) : "*";

        $isSettedlias = isset($alias) ? " AS ". $alias : " ";

        $clausSentence = " COUNT($columnsImploded)" . $isSettedlias . " ";

        //a clausula count vai ser tratada como se fosse uma coluna
        return $this->actualSelectArr = array_merge($this->actualSelectArr, [$clausSentence]);
    }
}