<?php

namespace QueryBuilder\clauses;

class FromClauses
{
    private array $actualTables;
    public function __construct(array &$actualTables) {
        $this->actualTables = &$actualTables;
    }
    public function from(array $newTables)
    {
        return array_merge($this->actualTables, $newTables);
    }
}