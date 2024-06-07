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
    protected $groupByItems = [];
    protected $orderByItems = [];
    protected $orderByDirection ;
    protected $limit;
    protected $offset;

    
    protected function buildSelect() {
        return empty($this->select) ? '*' : implode(', ', $this->select);
    }
    protected function buildFrom() 
    {
        return  implode(",", $this->tables);
    }
    /**
     * @summary : Metodo que vai retornar todos os 'wheres' formatados, evitando problemas como a passagem de um and sem um where anteriormente
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @return string
     */
    protected function buildWhere() {
        //adicionar logica pois ele concatena passando um and para cada where, mas se for um or por exemplo, dá erro 

        //verificar em cada string de where se NAO ha aS clausulaS AND, OR, LIKE, ILIKE, E NESTES ADICIONAR O AND
        return empty($this->where) ? ' ' : 'WHERE ' . implode(' AND ', $this->where);
    }

    protected function buildJoin() {
        return !empty($this->join) ? implode(' ', $this->join) : "";
    }

    protected function buildGroupBy() {
        return empty($this->groupByItems) ? '' : ' GROUP BY ' . implode(', ', $this->groupByItems);
    }

    protected function buildHaving() {
        return empty($this->having) ? '' : ' HAVING ' . implode(' AND ', $this->having);
    }

    protected function buildOrderBy() {
        return empty($this->orderByItems) ? '' : ' ORDER BY ' . implode(', ', $this->orderByItems). " $this->orderByDirection ";
    }

    protected function buildLimit() {
        return isset($this->limit) ? ' LIMIT ' . ":$this->limit" : '';
    }

    protected function buildOffset() {
        return isset($this->offset) ? ' OFFSET ' . ":$this->offset" : '';
    }

    protected function toSql() {
        $sql  = ' SELECT ' . $this->buildSelect();
        $sql .= ' FROM ' . $this->buildFrom();
        $sql .= ' ' .      $this->buildJoin();
        $sql .= ' WHERE '. $this->buildWhere();
        $sql .= ' ' .      $this->buildGroupBy();
        $sql .= ' ' .      $this->buildHaving();
        $sql .= ' ' .      $this->buildOrderBy();
        $sql .= ' ' .      $this->buildLimit();
        $sql .= ' ' .      $this->buildOffset();

        return $sql;
    }
    
}