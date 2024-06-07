<?php

namespace QueryBuilder\clauses;

use QueryBuilder\bootstrap\BaseQuery;

class OrderByClauses extends BaseQuery
{
    public function orderBy(array $columns)
    {
        return array_merge($this->orderByItems, $columns);
    }
}