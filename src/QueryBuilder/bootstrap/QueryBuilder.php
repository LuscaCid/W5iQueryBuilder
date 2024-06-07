<?php
namespace QueryBuilder\bootstrap;

use QueryBuilder\bootstrap\BaseQuery;
use QueryBuilder\clauses\GroupByClauses;
use QueryBuilder\clauses\HavingClauses;
use QueryBuilder\clauses\JoinClauses;
use QueryBuilder\clauses\LimitClauses;
use QueryBuilder\clauses\OffsetClauses;
use QueryBuilder\clauses\OrderByClauses;
use QueryBuilder\clauses\SelectClauses;
use QueryBuilder\clauses\WhereClauses;

class QueryBuilder extends BaseQuery 
{
    private SelectClauses $selectClauses;
    private WhereClauses $whereClauses;
    private HavingClauses $havingClauses;
    private JoinClauses $joinClauses;
    private OrderByClauses $orderByClauses;
    private LimitClauses $limitClauses;
    private OffsetClauses $offsetClauses;
    private GroupByClauses $groupByClauses;

    public function __construct(mixed $table = NULL)
    {
        $this->tables[] = is_array($table) ? array_merge($this->tables, $table) : $table;
        $this->selectClauses  = new SelectClauses($this->select);
        $this->whereClauses   = new WhereClauses($this->bindValues, $this->placeholderValues);
        $this->havingClauses  = new HavingClauses($this->bindValues, $this->placeholderValues);
        $this->limitClauses   = new LimitClauses($this->bindValues);
        $this->offsetClauses  = new OffsetClauses($this->bindValues);
        $this->joinClauses    = new JoinClauses();
        $this->orderByClauses = new OrderByClauses();
        $this->groupByClauses = new GroupByClauses();
    }
    public function select(array $columns) 
    {   
        $this->select = $this->selectClauses->select($columns);
        return $this;
    }
    public function selectCount(array $columns) 
    {
        $this->select = $this->selectClauses->selectCount($columns);
        return $this;
    }
    public function from(array $tables) 
    {
        $this->tables = $tables;
        return $this;   
    }
    public function groupBy(array $columns)
    {
        $this->groupByItems[] = $this->groupByClauses->groupBy($this->groupByItems, $columns); 
        return $this;
    }
    public function where(string $column, string $operator, string $value) 
    {
        $this->where[] = $this->where[]=$this->whereClauses->where($column, $operator, $value);
        return $this;
    }
    public function whereBetween($column, $start, $end) 
    {
        $this->where[] = $this->where[]=$this->whereClauses->whereBetween($column, $start, $end);
        return $this;
    }
    public function whereIn($column, array $values) 
    {
        $this->where[] = $this->where[]=$this->whereClauses->whereIn($column, $values);
        return $this;
    }
    public function whereNotIn($column, $values) 
    {
        $this->where[] = $this->whereClauses->whereNotIn($column, $values);
        return $this;
    }
    public function andWhereLike(string $column, mixed $value) 
    {
        $this->where[] = $this->whereClauses->andWhereLike($column, $value);
        return $this;
    }
    public function andWhereILike(string $column, mixed $value)  
    {
        $this->where[] = $this->whereClauses->andWhereILike($column, $value);
        return $this;
    }
    public function orWhereLike(string $column, mixed $value)  
    {
        $this->where[] = $this->whereClauses->orWhereLike($column, $value); 
        return $this;
    }
    public function orWhereILike(string $column, mixed $value) 
    {
        $this->where[] = $this->whereClauses->orWhereILike($column, $value);
        return $this;
    }
    public function andWhere(string $column, string $operator, string $value)
    {
        $this->where[] = $this->whereClauses->andWhere($column, $operator, $value);
        return $this;
    }
    public function orWhere(string $column, string $operator, string $value) 
    {
        $this->where[] = $this->whereClauses->orWhere($column, $operator, $value);
        return $this;
    }
    public function andWhereIn (string $column, array $value) 
    {
        $this->where[] = $this->where[]=$this->whereClauses->andWhereIn($column, $value);
        return $this;
    }
    public function orWhereIn (string $column, array $value) 
    {
       $this->where[] = $this->whereClauses->orWhereIn($column, $value);
        return $this;
    }
    public function andWhereBetween (string $column, mixed $start, mixed $end) 
    {
        $this->where[] = $this->where[]= $this->whereClauses->andWhereBetween($column ,$start, $end);
        return $this;
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

    public function innerJoin(string $table, string $rightSide, string $operator, string $leftSide) 
    {
        $this->join[] = $this->joinClauses->innerJoin($table, $rightSide, $operator, $leftSide);
        return $this;
    }
    public function join(string $table, string $rightSide, string $operator, string $leftSide) 
    {
        $this->join[] =  $this->joinClauses->innerJoin($table, $rightSide, $operator, $leftSide);
        return $this;
    }
    
    public function leftJoin(string $table, string $operator, string $rightSide ,string $leftSide) 
    {
        $this->join[] = $this->joinClauses->leftJoin($table, $rightSide ,$operator, $leftSide);
        return $this;
    }
    public function rightJoin(string $table, string $operator, string $rightSide ,string $leftSide) 
    {
        $this->join[] = $this->joinClauses->rightJoin($table, $rightSide ,$operator, $leftSide);
        return $this;
    }
    public function orderBy(array $columns, $direction = 'ASC') 
    {
        $this->orderByDirection = $direction;
        $this->orderByItems[] = $this->orderByClauses->orderBy($columns);
        return $this;
    }

    public function limit($limit) 
    {
        $this->limit = $this->limitClauses->limit($limit);
        return $this;
    }
    public function offset($offset) 
    {
        $this->offset = $this->offsetClauses->offset($offset);
        return $this;
    }
    public function load(bool $isValueable = false)  
    {
        $query = $this->toSql();
        return $this->fetchAll($query, $isValueable);
    }
    public function first() 
    {
        $query = $this->toSql();
        return $this->fetchObject($query);
    }

}