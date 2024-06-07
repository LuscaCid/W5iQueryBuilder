<?php

use QueryBuilder\bootstrap\BaseQuery;

class OffsetClause extends BaseQuery 
{
    public function offset(int $offset = 0)
    {
        $this->bindValues[]= ["offset" => $offset];
    
        $this->offset = " :offset " ;
        return $this;
    }
} 