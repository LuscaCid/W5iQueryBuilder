<?php

namespace QueryBuilder\clauses;

class GroupByClauses
{
    private array|null $groupByItems;
    public function __construct(array|null &$groupByItems)
    {
        $this->groupByItems = &$groupByItems;
    }
    public function groupBy(array $groupByItems)
    {
        return $this->groupByItems[] = array_merge($this->groupByItems ,$groupByItems);
    }
}