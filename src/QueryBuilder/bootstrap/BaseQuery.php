<?php

namespace QueryBuilder\bootstrap;

class BaseQuery 
{
    protected $bindValues = [];
    protected $placeholderValues;
    protected $placeholders;
    protected $select = [];
    protected $from = [];
    protected $where = [];
    protected $having = [];
    protected $join = [];
    protected $orderBy = [];
    protected $groupBy = [];
    protected $limit;
    protected $offset;

    public function select(array $columns) 
    {   
        $this->select = array_merge($this->select, $columns);
        return $this;
    }
    public function from(string $table) 
    {
        $this->from = $table;
        return $this;   
    }
    
    public function groupBy($fields) {
        $this->groupBy = array_merge($this->groupBy, (array) $fields);
        return $this;
    }

    public function having($condition) {
        $this->having[] = $condition;
        return $this;
    }

    public function orderBy($field, $direction = 'ASC') {
        $this->orderBy[] = "$field $direction";
        return $this;
    }

    public function limit($limit) {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset) {
        $this->offset = $offset;
        return $this;
    }

    protected function buildSelect() {
        return empty($this->select) ? '*' : implode(', ', $this->select);
    }

    protected function buildFrom() {
        return implode(', ', $this->from);
    }

    protected function buildWhere() {
        return empty($this->where) ? '' : 'WHERE ' . implode(' AND ', $this->where);
    }

    protected function buildJoin() {
        return implode(' ', $this->join);
    }

    protected function buildGroupBy() {
        return empty($this->groupBy) ? '' : 'GROUP BY ' . implode(', ', $this->groupBy);
    }

    protected function buildHaving() {
        return empty($this->having) ? '' : 'HAVING ' . implode(' AND ', $this->having);
    }

    protected function buildOrderBy() {
        return empty($this->orderBy) ? '' : 'ORDER BY ' . implode(', ', $this->orderBy);
    }

    protected function buildLimit() {
        return isset($this->limit) ? ' LIMIT ' . $this->limit : '';
    }

    protected function buildOffset() {
        return isset($this->offset) ? ' OFFSET ' . $this->offset : '';
    }

    public function toSql() {
        $sql = 'SELECT ' . $this->buildSelect();
        $sql .= ' FROM ' . $this->buildFrom();
        $sql .= ' ' .      $this->buildJoin();
        $sql .= ' ' .      $this->buildWhere();
        $sql .= ' ' .      $this->buildGroupBy();
        $sql .= ' ' .      $this->buildHaving();
        $sql .= ' ' .      $this->buildOrderBy();
        $sql .= ' ' .      $this->buildLimit();
        $sql .= ' ' .      $this->buildOffset();

        return $sql;
    }
    
}