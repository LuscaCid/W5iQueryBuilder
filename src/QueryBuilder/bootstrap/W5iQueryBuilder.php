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

/**
 * @summary : possiveis chamadas para o where, caso apenas dois argumentos, o código irá subentender que será passado para uma comparação de igualdade
 * @author : Lucas Felipe Lima Cid
 * @created 07/06/2024
 * @method mixed where(string $column, string|int|bool $value) 
 * @method mixed where(string $column, string $operator, string|int|bool $value) 
 */
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
     * @summary : Funcção construtora para utilização da classe e seus respectivos metodos.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
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
            $this->tables = [];
            $this->tables = array_merge($this->tables, $tables);
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
        $this->orderByClauses = new OrderByClauses($this->orderByItems);
        $this->groupByClauses = new GroupByClauses($this->groupByItems);
        $this->joinClauses    = new JoinClauses();
    }

    /**
     * @summary : Seleciona colunas especificadas ou todas se não especificadas.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param array|null $columns
     * @return self
     */
    public function select(array|null $columns = NULL) 
    {   
        //somente na chamada será possível obter todos os itens sem necessariamente passar '["*]'
        $this->select = $this->selectClauses->select($columns ? $columns : ["*"]);
        return $this;
    }

    /**
     * @summary : Seleciona contagem de colunas especificadas com alias opcional.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param array $columns
     * @param string $alias
     * @return self
     */
    public function selectCount(array $columns = NULL, string $alias = NULL) 
    {
        $this->select = $this->selectClauses->selectCount($columns, $alias);
        return $this;
    }

    /**
     * @summary : Define a tabela de origem para a consulta.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param array $tables
     * @return self
     */
    public function from(array $tables) 
    {
        $this->tables = $this->fromClauses->from($tables);
        return $this;   
    }

    /**
     * @summary : Adiciona cláusulas GROUP BY à consulta.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param array $columns
     * @return self
     */
    public function groupBy(array $columns)
    {
        $this->groupByItems = $this->groupByClauses->groupBy($columns); 
        return $this;
    }

    /**
     * @summary : Adiciona cláusula WHERE BETWEEN.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $column
     * @param mixed $start
     * @param mixed $end
     * @return self
     */
    public function whereBetween($column, $start, $end) 
    {
        $this->where[] = $this->whereClauses->whereBetween($column, $start, $end);
        return $this;
    }

    /**
     * @summary : Adiciona cláusula AND WHERE BETWEEN.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $column
     * @param mixed $start
     * @param mixed $end
     * @return self
     */
    public function andWhereBetween($column, $start, $end) 
    {
        $this->where[] = $this->whereClauses->andWhereBetween($column, $start, $end);
        return $this;
    }

    /**
     * @summary : Adiciona cláusula OR WHERE BETWEEN.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $column
     * @param mixed $start
     * @param mixed $end
     * @return self
     */
    public function orWhereBetween($column, $start, $end) 
    {
        $this->where[] = $this->whereClauses->orWhereBetween($column, $start, $end);
        return $this;
    }

    /**
     * @summary : Adiciona cláusula WHERE IN.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $column
     * @param array|null $values
     * @return self
     */
    public function whereIn($column, array|null $values) 
    {
        $this->where[] = $this->whereClauses->whereIn($column, $values);
        return $this;
    }

    /**
     * @summary : Adiciona cláusula WHERE NOT IN.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $column
     * @param array|null $values
     * @return self
     */
    public function whereNotIn($column,  array|null $values) 
    {
        $this->where[] = $this->whereClauses->whereNotIn($column, $values);
        return $this;
    }

    /**
     * @summary : Adiciona cláusula AND WHERE NOT IN.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $column
     * @param array|null $values
     * @return self
     */
    public function andWhereNotIn(string $column, array|null  $values) 
    {
        $this->where[] = $this->whereClauses->andWhereNotIn($column, $values);
        return $this;
    }

    /**
     * @summary : Adiciona cláusula OR WHERE NOT IN.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $column
     * @param array|null $values
     * @return self
     */
    public function orWhereNotIn(string $column, array|null  $values) 
    {
        $this->where[] = $this->whereClauses->orWhereNotIn($column, $values);
        return $this;
    }

    /**
     * @summary : Adiciona cláusula AND WHERE LIKE.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $column
     * @param mixed $value
     * @return self
     */
    public function andWhereLike(string $column, mixed $value) 
    {
        $this->where[] = $this->whereClauses->andWhereLike($column, $value);
        return $this;
    }

    /**
     * @summary : Adiciona cláusula AND WHERE ILIKE.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $column
     * @param mixed $value
     * @return self
     */
    public function andWhereILike(string $column, mixed $value)  
    {
        $this->where[] = $this->whereClauses->andWhereILike($column, $value);
        return $this;
    }

    /**
     * @summary : Adiciona cláusula OR WHERE LIKE.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $column
     * @param mixed $value
     * @return self
     */
    public function orWhereLike(string $column, mixed $value)  
    {
        $this->where[] = $this->whereClauses->orWhereLike($column, $value); 
        return $this;
    }

    /**
     * @summary : Adiciona cláusula OR WHERE ILIKE.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $column
     * @param mixed $value
     * @return self
     */
    public function orWhereILike(string $column, mixed $value) 
    {
        $this->where[] = $this->whereClauses->orWhereILike($column, $value);
        return $this;
    }

    /**
     * @summary : Adiciona cláusula AND WHERE.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @return self
     */
    public function andWhere(string $column, string $operator, mixed $value)
    {
        $this->where[] = $this->whereClauses->andWhere($column, $operator, $value);
        return $this;
    }

    /**
     * @summary : Adiciona cláusula OR WHERE.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @return self
     */
    public function orWhere(string $column, string $operator, mixed $value) 
    {
        $this->where[] = $this->whereClauses->orWhere($column, $operator, $value);
        return $this;
    }

    /**
     * @summary : Adiciona cláusula AND WHERE IN.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $column
     * @param array|null $value
     * @return self
     */
    public function andWhereIn (string $column, array|null $value) 
    {
        $this->where[] = $this->whereClauses->andWhereIn($column, $value);
        return $this;
    }

    /**
     * @summary : Adiciona cláusula OR WHERE IN.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $column
     * @param array|null $value
     * @return self
     */
    public function orWhereIn (string $column, array|null $value) 
    {
        $this->where[] = $this->whereClauses->orWhereIn($column, $value);
        return $this;
    }

    /**
     * @summary : Adiciona cláusula HAVING.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $column
     * @param string $operator
     * @param string $value
     * @return self
     */
    public function having(string $column, string $operator, string $value) {
        $this->having[] = $this->havingClauses->having($column, $operator, $value);
        return $this;
    }

    /**
     * @summary : Adiciona cláusula HAVING IN.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $column
     * @param array $items
     * @return self
     */
    public function havingIn(string $column, array $items) 
    {
        $this->having[] = $this->havingClauses->havingIn($column, $items);
        return $this;
    }

    // public function havingSum(string $column, $operator, $value) {
    //     $this->havingClauses->havingSum($column, $operator, $value);
    //     return $this;
    // }

    /**
     * @summary : Adiciona cláusula INNER JOIN.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $table
     * @param string $rightSide
     * @param string $operator
     * @param string $leftSide
     * @return self
     */
    public function innerJoin(string $table, string $rightSide, string $operator, string $leftSide) 
    {
        $this->join[] = $this->joinClauses->innerJoin($table, $rightSide, $operator, $leftSide);
        return $this;
    }

    /**
     * @summary : Adiciona cláusula JOIN.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $table
     * @param string $rightSide
     * @param string $operator
     * @param string $leftSide
     * @return self
     */
    public function join(string $table, string $rightSide, string $operator, string $leftSide) 
    {
        $this->join[] =  $this->joinClauses->innerJoin($table, $rightSide, $operator, $leftSide);
        return $this;
    }
    
    /**
     * @summary : Adiciona cláusula LEFT JOIN.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $table
     * @param string $rightSide
     * @param string $operator
     * @param string $leftSide
     * @return self
     */
    public function leftJoin(string $table, string $rightSide, string $operator ,string $leftSide) 
    {
        $this->join[] = $this->joinClauses->leftJoin($table, $rightSide ,$operator, $leftSide);
        return $this;
    }

    /**
     * @summary : Adiciona cláusula RIGHT JOIN.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $table
     * @param string $rightSide
     * @param string $operator
     * @param string $leftSide
     * @return self
     */
    public function rightJoin(string $table, string $rightSide, string $operator ,string $leftSide) 
    {
        $this->join[] = $this->joinClauses->rightJoin($table, $rightSide ,$operator, $leftSide);
        return $this;
    }

    /**
     * @summary : Adiciona cláusula ORDER BY.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param array $columns
     * @param string $direction
     * @return self
     */
    public function orderBy(array $columns, $direction = 'ASC') 
    {
        $this->orderByDirection = $direction;
        $this->orderByItems = $this->orderByClauses->orderBy($columns);
        return $this;
    }

    /**
     * @summary : Adiciona cláusula LIMIT.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param int $limit
     * @return self
     */
    public function limit($limit) 
    {
        $this->limit = $this->limitClauses->limit($limit);
        return $this;
    }

    /**
     * @summary : Adiciona cláusula OFFSET.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param int $offset
     * @return self
     */
    public function offset($offset) 
    {
        $this->offset = $this->offsetClauses->offset($offset);
        return $this;
    }

    /**
     * @summary : Executa a consulta e retorna todos os resultados.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param bool $isValueable
     * @return mixed
     */
    public function load(bool $isValueable = false)  
    {
        $query = $this->toSql();
        return $this->fetchAll($query, $isValueable);
    }

    /**
     * @summary : Executa a consulta e retorna o primeiro resultado.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @return mixed
     */
    public function first() 
    {
        $query = $this->toSql();
        return $this->fetchObject($query);
    }

    /**
     * @summary : Retorna a consulta SQL montada.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @return mixed
     */
    public function getQuery():mixed
    {
        return $this->toSql();
    }
   
    /**
     * @summary : Manipula chamadas de métodos indefinidos.
     * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
     * @param string $name
     * @param array $arguments
     * @return mixed
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
}
