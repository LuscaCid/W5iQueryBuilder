<?php
namespace QueryBuilder\clauses;

use QueryBuilder\bootstrap\BaseQuery;

class JoinClauses extends BaseQuery 
{

    public function innerJoin (mixed $table, string $leftSide, string $operator, string $rightSide ) 
    {
        $this->join[]= " INNER JOIN ".$table." ON ".$leftSide." ". $operator. " ". $rightSide ;
        return $this;
    }
    public function leftJoin (string $table, string $leftSide, string $operator, string $rightSide ) 
    {
        $this->join[]= " LEFT JOIN ".$table." ON ".$leftSide." ". $operator. " ". $rightSide ;
        return $this;
    }

    public function rightJoin (mixed $table, string $leftSide, string $operator, string $rightSide ) 
    {
        $this->join[]= " RIGHT JOIN ".$table." ON ".$leftSide." ". $operator. " ". $rightSide ;
        return $this;
    }
}