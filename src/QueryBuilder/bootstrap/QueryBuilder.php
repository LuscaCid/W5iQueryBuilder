<?php

use QueryBuilder\bootstrap\BaseQuery;
use QueryBuilder\clauses\WhereClauses;

class QueryBuilder extends BaseQuery 
{
    protected $whereClause;
    protected $havingClause;
    protected $joinClause;
    protected $orderByClause;
    protected $limitClause;

    public function __construct() {
        $this->whereClause   = new WhereClauses();
        $this->havingClause  = new HavingClause();
        $this->joinClause    = new JoinClause();
        $this->orderByClause = new OrderByClause();
        $this->limitClause   = new LimitClause();
    }

    public function where($condition) {
        $this->whereClause->where($condition);
        return $this;
    }

    public function whereBetween($field, $start, $end) {
        $this->whereClause->whereBetween($field, $start, $end);
        return $this;
    }

    public function whereIn($field, array $values) {
        $this->whereClause->whereIn($field, $values);
        return $this;
    }

    public function having($condition) {
        $this->havingClause->having($condition);
        return $this;
    }

    public function havingCount($field, $operator, $value) {
        $this->havingClause->havingCount($field, $operator, $value);
        return $this;
    }

    public function havingSum($field, $operator, $value) {
        $this->havingClause->havingSum($field, $operator, $value);
        return $this;
    }

    public function join($table, $condition, $type = 'INNER') {
        $this->joinClause->join($table, $condition, $type);
        return $this;
    }

    public function orderBy($field, $direction = 'ASC') {
        $this->orderByClause->orderBy($field, $direction);
        return $this;
    }

    public function limit($limit) {
        $this->limitClause->limit($limit);
        return $this;
    }

    public function toSql() {
        $this->where = $this->whereClause->getWhere();
        $this->having = $this->havingClause->getHaving();
        $this->join = $this->joinClause->getJoin();
        $this->orderBy = $this->orderByClause->getOrderBy();
        $this->limit = $this->limitClause->getLimit();

        return parent::toSql();
    }

}