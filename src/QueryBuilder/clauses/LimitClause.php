<?php
namespace QueryBuilder\clauses;

class LimitClauses 
{
    private array $bindValues = [];
    public function __construct(array &$bindValues)
    {
        $this->bindValues = &$bindValues;
    }
    public function limit(int $limit = 10)
    {
        $this->bindValues[]= ["limit" => $limit];
        return " :limit " ;
    }
} 