<?php
namespace QueryBuilder\clauses;

class OffsetClauses
{
    private array $bindValues = [];
    public function __construct(array &$bindValues)
    {
        $this->bindValues = &$bindValues;
    }
    public function offset(int $offset = 0)
    {
        $this->bindValues[] = ["offset" => $offset];
    
        return " :offset " ;
        
    }
} 