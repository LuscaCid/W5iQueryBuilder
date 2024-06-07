<?php

namespace QueryBuilder\bootstrap;

use QueryBuilder\core\W5IQueryBuilderCore;

abstract class BaseQuery extends W5IQueryBuilderCore
{
    protected $bindValues = [];
    protected $placeholderValues;
    protected $placeholders;
    protected $select = [];
    protected $tables = [];
    protected $where = [];
    protected $having = [];
    protected $join = [];
    protected $groupBy = [];
    protected $orderByItems = [];
    protected $orderByDirection ;
    protected $limit;
    protected $offset;

    protected function select(array $columns) 
    {   
        $this->select = array_merge($this->select, $columns);
        return $this;
    }
    protected function from(array $tables) 
    {
        $this->tables = $tables;
        return $this;   
    }
    
    protected function groupBy($fields) {
        $this->groupBy = array_merge($this->groupBy, (array) $fields);
        return $this;
    }

    protected function limit($limit) {
        $this->limit = $limit;
        return $this;
    }

    protected function offset($offset) {
        $this->offset = $offset;
        return $this;
    }

    protected function buildSelect() {
        return empty($this->select) ? '*' : implode(', ', $this->select);
    }
    protected function buildFrom() 
    {
        return  implode(",", $this->tables);
    }
    protected function buildWhere() {
        //adicionar logica pois ele concatena passando um and para cada where, mas se for um or por exemplo, dá erro 
        return empty($this->where) ? '' : 'WHERE ' . implode(' AND ', $this->where);
    }

    protected function buildJoin() {
        return !empty($this->join) ? implode(' ', $this->join) : "";
    }

    protected function buildGroupBy() {
        return empty($this->groupBy) ? '' : 'GROUP BY ' . implode(', ', $this->groupBy);
    }

    protected function buildHaving() {
        return empty($this->having) ? '' : 'HAVING ' . implode(' AND ', $this->having);
    }

    protected function buildOrderBy() {
        return empty($this->orderByItems) ? '' : 'ORDER BY ' . implode(', ', $this->orderByItems). " $this->orderByDirection ";
    }

    protected function buildLimit() {
        return isset($this->limit) ? ' LIMIT ' . $this->limit : '';
    }

    protected function buildOffset() {
        return isset($this->offset) ? ' OFFSET ' . $this->offset : '';
    }

    protected function toSql() {
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