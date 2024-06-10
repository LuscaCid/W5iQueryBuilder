<?php

namespace QueryBuilder\clauses;

class FromClauses
{
    private array|null $actualTables;
    public function __construct(array|null &$actualTables) 
    {
        $this->actualTables = &$actualTables;
    }
    public function from(array $newTables)
    {
        $this->actualTables = [];
        return $this->actualTables[] =  $newTables;
    }
}