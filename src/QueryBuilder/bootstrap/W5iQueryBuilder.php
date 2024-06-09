<?php
namespace QueryBuilder\bootstrap;

use QueryBuilder\bootstrap\BaseQuery;
use QueryBuilder\clauses\FromClauses;
use QueryBuilder\clauses\GroupByClauses;
use QueryBuilder\clauses\HavingClauses;
use QueryBuilder\clauses\JoinClauses;
use QueryBuilder\clauses\LimitClauses;
use QueryBuilder\clauses\OffsetClauses;
use QueryBuilder\clauses\OrderByClauses;
use QueryBuilder\clauses\SelectClauses;
use QueryBuilder\clauses\WhereClauses;
use QueryBuilder\config\DataBaseSettings;

class W5iQueryBuilder extends BaseQuery 
{
    private SelectClauses $selectClauses;
    private FromClauses $fromClauses;
    private WhereClauses $whereClauses;
    private HavingClauses $havingClauses;
    private JoinClauses $joinClauses;
    private OrderByClauses $orderByClauses;
    private LimitClauses $limitClauses;
    private OffsetClauses $offsetClauses;
    private GroupByClauses $groupByClauses;

    /**
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @summary : Funcção construtora para utilização da classe e seus respectivos metodos, podendo ser .
     * @param array|string $tables : podendo ser null para que as tabelas possam ser passadas posteriormente no metodo "from", mas também podem ser passadas a(s) tabela(s) as quais sofrerão a consulta 
     * @param string $otherUnit : Outra unidade caso for necessario abrir outra transacao com outra base de dados. 
     */
    public function __construct(mixed $tables = NULL, string $otherUnit = NULL)
    {
        $instance = DataBaseSettings::getInstance();
      
        //caso o usuario queira mudar o banco o qual sofrerá a transacao mantendo a mesma senha e username
        if (isset($otherUnit) && is_string($otherUnit) ) 
        {
            $instance->setDatabaseConfigByKey("dbName", $otherUnit);

        }
        if (is_array($tables)) 
        {
            $this->tables[] = array_merge($this->tables, $tables);
        } else if (is_string($tables)) 
        {
            $this->tables[] = $tables;
        }
        $this->selectClauses  = new SelectClauses($this->select);
        $this->fromClauses    = new FromClauses($this->tables);
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
    /**
     * @param array $columns : Colunas que sofrerao o select como sendo count
     * @param string $alias : Apelido que seria passado em ex: "select count(*) as qt_items from tabela"
     */
    public function selectCount(array $columns = NULL, string $alias = NULL) 
    {
        $this->select = $this->selectClauses->selectCount($columns, $alias);
        return $this;
    }
    public function from(array $tables) 
    {
        $this->tables[] = $this->fromClauses->from($tables);
        return $this;   
    }
    public function groupBy(array $columns)
    {
        $this->groupByItems[] = $this->groupByClauses->groupBy($this->groupByItems, $columns); 
        return $this;
    }

    /**
     * @summary : possiveis chamadas para o where, caso apenas dois argumentos, o código irá subentender que será passado para uma comparação de igualdade
     * @author : Lucas Felipe Lima Cid
     * @created 07/06/2024
     */
    public function __call($name, $arguments)
    {
        switch ($name)
        {
            case 'where':
                switch (count($arguments)) 
                {
                    case 2:
                        $this->where[] = $this->whereClauses->where($arguments[0], $arguments[1]);
                        return $this;
                    case 3:
                        $this->where[]= $this->whereClauses->where3Args($arguments[0], $arguments[1], $arguments[2]);
                        return $this;
                }
        } 
    }    
    public function whereBetween($column, $start, $end) 
    {
        $this->where[] = $this->whereClauses->whereBetween($column, $start, $end);
        return $this;
    }
    public function andWhereBetween($column, $start, $end) 
    {
        $this->where[] = $this->whereClauses->andWhereBetween($column, $start, $end);
        return $this;
    }
    public function orWhereBetween($column, $start, $end) 
    {
        $this->where[] = $this->whereClauses->orWhereBetween($column, $start, $end);
        return $this;
    }
    public function whereIn($column, array|null $values) 
    {
        $this->where[] = $this->whereClauses->whereIn($column, $values);
        return $this;
    }
    public function whereNotIn($column,  array|null $values) 
    {
        $this->where[] = $this->whereClauses->whereNotIn($column, $values);
        return $this;
    }
    public function andWhereNotIn(string $column, array|null  $values) 
    {
        $this->where[] = $this->whereClauses->andWhereNotIn($column, $values);
        return $this;
    }
    public function orWhereNotIn(string $column, array|null  $values) 
    {
        $this->where[] = $this->whereClauses->orWhereNotIn($column, $values);
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
    public function having(string $column, string $operator, string $value) {
        $this->having[] = $this->havingClauses->having($column, $operator, $value);
        return $this;
    }
    public function havingIn(string $column, array $items) 
    {
        $this->having[] = $this->havingClauses->havingIn($column, $items);
        return $this;
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
    /**
     * @sumary Apenas retorna a query montada.
     * @author Lucas Cid
     * @return mixed
     */
    public function getQuery()  :mixed
    {
        return $this->toSql();
    }
}