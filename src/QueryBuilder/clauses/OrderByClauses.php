<?php

namespace QueryBuilder\clauses;

class OrderByClauses
{
    private array $orderByItems;
    public function __construct(array &$orderByItems)
    {
        $this->orderByItems = &$orderByItems;
    }
    public function orderBy(array $columns)
    {
        return $this->orderByItems[] = $columns;
    }
}