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
    public function selectCount(array $columns = NULL, $alias = NULL) 
    {
        $columnsImploded = is_array($columns) ? implode(",", $columns) : "*";

        $isSettedlias = isset($alias) ? " AS ". $alias : " ";

        $clausSentence = " COUNT($columnsImploded)" . $isSettedlias . " ";

        //a clausula count vai ser tratada como se fosse uma coluna
        $this->actualSelectArr = array_merge($this->actualSelectArr, [$clausSentence]);
        return $this->actualSelectArr;
    }
}