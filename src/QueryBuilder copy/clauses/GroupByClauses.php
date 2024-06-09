<?php

namespace QueryBuilder\clauses;

class GroupByClauses
{
    public function groupBy(array $orderByItems, array $newColumns)
    {
        return array_merge($orderByItems, $newColumns);
    }
}