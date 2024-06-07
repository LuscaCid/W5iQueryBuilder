<?php

use QueryBuilder\bootstrap\BaseQuery;
use QueryBuilder\clauses\HavingClauses;
use QueryBuilder\clauses\JoinClauses;
use QueryBuilder\clauses\WhereClauses;

class QueryBuilder extends BaseQuery 
{
    protected WhereClauses $whereClauses;
    protected HavingClauses $havingClauses;
    protected JoinClauses $joinClauses;
    protected $orderByClauses;
    protected $limitClause;

    public function __construct(string $unit) {
        $this->whereClauses  = new WhereClauses();
        $this->joinClauses   = new JoinClauses();
        $this->havingClauses = new HavingClauses();
    }
    
    public function groupBy($columns)
    {
        $this->groupBy = array_merge($this->groupBy, $columns);
        return $this;
    }
    public function where(string $column, string $operator, string $value) {
        $this->whereClauses->where($column, $operator, $value);
        return $this;
    }

    public function whereBetween($column, $start, $end) {
        $this->whereClauses->whereBetween($column, $start, $end);
        return $this;
    }

    public function whereIn($column, array $values) {
        $this->whereClauses->whereIn($column, $values);
        return $this;
    }
    public function whereNotIn($column, $values) 
    {
        $this->whereClauses->whereNotIn($column, $values);
        return $this;
    }
    public function andWhereLike(string $column, mixed $value) 
    {
        $this->whereClauses->andWhereLike($column, $value);
    }
    public function having(string $column, string $operator, string $value) {
        $this->havingClauses->having($column, $operator, $value);
        return $this;
    }
    public function havingIn(string $column, array $items) 
    {
        $this->havingClauses->havingIn($column, $items);
    }
    // public function havingSum(string $column, $operator, $value) {
    //     $this->havingClauses->havingSum($column, $operator, $value);
    //     return $this;
    // }

    public function innerJoin(string $table, string $rightSide, string $operator, string $leftSide) {
        $this->joinClauses->innerJoin($table, $rightSide, $operator, $leftSide);
        return $this;
    }
    public function join(string $table, string $rightSide, string $operator, string $leftSide) 
    {
        $this->joinClauses->innerJoin($table, $rightSide, $operator, $leftSide);
        return $this;
    }
    public function orderBy(array $columns, $direction = 'ASC') {
        $this->orderByDirection = $direction;

        $this->orderByClauses->orderBy($columns);
        return $this;
    }

    public function limit($limit) {
        $this->limitClause->limit($limit);
        return $this;
    }

}