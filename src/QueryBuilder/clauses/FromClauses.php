<?php

namespace QueryBuilder\clauses;

class FromClauses
{
    private array $actualTables;
    public function __construct(array &$actualTables) 
    {
        $this->actualTables = &$actualTables;
    }
    public function from(array $newTables)
    {
       return $this->actualTables[] =  $newTables;
    }
}