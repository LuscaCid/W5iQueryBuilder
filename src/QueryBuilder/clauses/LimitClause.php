<?php

use QueryBuilder\bootstrap\BaseQuery;

class OffsetClause extends BaseQuery 
{
    public function limit(int $limit = 10)
    {
        $this->bindValues[]= ["limit" => $limit];
    
        $this->limit = " :limit " ;
        return $this;
    }
} 