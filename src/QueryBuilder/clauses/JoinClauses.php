<?php
namespace QueryBuilder\clauses;

/**
 * @summary : Classe para lidar com as clausulas Joins 
 * @author Lucas Cid
 */
class JoinClauses 
{
    public function innerJoin (string $table, string $leftSide, string $operator, string $rightSide ) 
    {
        return " INNER JOIN ".$table." ON ".$leftSide." ". $operator. " ". $rightSide. " " ;
    }
    public function leftJoin (string $table, string $leftSide, string $operator, string $rightSide ) 
    {
        return " LEFT JOIN ".$table." ON ".$leftSide." ". $operator. " ". $rightSide. " " ;
    }

    public function rightJoin (string $table, string $leftSide, string $operator, string $rightSide ) 
    {
        return " RIGHT JOIN ".$table." ON ".$leftSide." ". $operator. " ". $rightSide. " " ;
    }
    public function fullOuterJoin (string $table, string $leftSide, string $operator, string $rightSide) 
    {
        return " FULL OUTER JOIN ".$table." ON ".$leftSide." ". $operator. " ". $rightSide. " " ;
    }
}