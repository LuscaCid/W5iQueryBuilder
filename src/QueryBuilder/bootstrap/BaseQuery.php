<?php

namespace QueryBuilder\bootstrap;

use QueryBuilder\core\W5IQueryBuilderCore;

abstract class BaseQuery extends W5IQueryBuilderCore
{
    protected $select;
    protected $tables;
    protected $where = [];
    protected $having = [];
    protected $join = [];
    protected $groupByItems = [];
    protected $orderByItems = [];
    protected $orderByDirection ;
    protected $limit;
    protected $offset;

    
    protected function buildSelect() {
        if(isset($this->select) && empty($this->select)) 
        {
            return " SELECT * ";
        } 
        else if (isset($this->select) && !empty($this->select)) 
        {
            return " SELECT " . implode(', ', $this->select). " ";
        }
    }
    protected function buildFrom() 
    {
        if(isset($this->tables) && !empty($this->tables)) 
        {
            return " FROM ". implode(",", $this->tables);
        }
    }
    /**
     * @summary : Metodo que vai retornar todos os 'wheres' formatados, evitando problemas como a passagem de um and sem um where anteriormente
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @return string
     */
    protected function buildWhere() {
        //adicionar logica pois ele concatena passando um and para cada where, mas se for um or por exemplo, dá erro 
        $whereClausesFormatted = [];
        if(!empty($this->where)) 
        {
            for($index = 0; $index < count($this->where); $index++) 
            {
                $condition = $this->where[$index] ;
                
                $contains = str_contains(strtoupper($condition), " AND ") || str_contains(strtoupper($condition), " OR ") ? TRUE : FALSE;

                if($contains && $index == 0) 
                {
                    if(str_contains(strtoupper($condition), " AND ")) 
                    {
                        $condition = str_replace("AND", "", $condition);
                    }
                    else if (str_contains(strtoupper($condition), " OR ")) 
                    {
                        $condition = str_replace("OR", "WHERE", $condition);
                    }
                    $whereClausesFormatted[] = " " . $condition . " ";
                }
                if(!$contains && $index > 0) 
                {
                    $whereClausesFormatted[] = " AND " . $condition . " ";
                    continue;
                }
                $whereClausesFormatted[] = $this->where[$index];
            }
        //verificar em cada string de where se NAO ha aS clausulaS AND, OR, LIKE, ILIKE, E NESTES ADICIONAR O AND
            return ' WHERE ' . implode(' ', $whereClausesFormatted);
        }
        return  ' '; 
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
        return isset($this->limit) ? ' LIMIT ' . "$this->limit" : ' ';
    }

    protected function buildOffset() {
        return isset($this->offset) ? ' OFFSET ' . "$this->offset" : ' ';
    }

    protected function toSql() {
        $sql  = ' ' . $this->buildSelect();
        $sql .= ' ' . $this->buildFrom();
        $sql .= ' ' . $this->buildJoin();
        $sql .= ' ' . $this->buildWhere();
        $sql .= ' ' . $this->buildGroupBy();
        $sql .= ' ' . $this->buildHaving();
        $sql .= ' ' . $this->buildOrderBy();
        $sql .= ' ' . $this->buildLimit();
        $sql .= ' ' . $this->buildOffset();

        return $sql;
    }
    
}