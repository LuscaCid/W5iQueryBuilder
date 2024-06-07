<?php

namespace QueryBuilder\clauses;

use QueryBuilder\bootstrap\BaseQuery;

class OrderByClauses extends BaseQuery
{
    public function orderBy(array $columns)
    {
        $this->orderByItems[] = array_merge($this->orderByItems, $columns);
        return $this;
    }
}