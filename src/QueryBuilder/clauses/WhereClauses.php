<?php
namespace QueryBuilder\clauses;

use QueryBuilder\bootstrap\BaseQuery;

class WhereClauses extends BaseQuery
{

    public function andWhereIn(string $column, array $items) 
    {
        $placeholders = implode(",", array_fill(0, count($items), "?"));

        $this->placeholderValues = array_merge($this->placeholderValues, $items );
        $this->where[] = " AND $column IN ( $placeholders ) ";

        return $this;
    }
    public function whereNotIn(string $column, array $items) 
    {
        //literalmente construindo uma string com placeholders de arcordo com  aquantudade q=e  placeholders
        $placeholders = implode(",", array_fill(0, count($items), "?"));

        $this->placeholderValues = array_merge($this->placeholderValues, $items );
        $this->where[] = " $column NOT IN ( $placeholders ) ";

        return $this;
        
    }
    public function andWhere(string $column, string $operator, string $value)  
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        $this->where[]= " AND " .$column. " ".$operator. " ". " :$bind ";
        return $this;
    }
    public function orWhere(string $column, string $operator, string $value) 
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        $this->where[]= " OR ". " $column " .$operator. " ". " :$bind ";
        return $this;
    }
    public function where(string $column, string $operator, string $value) 
    {

        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        $this->where[]= " WHERE ".$column." ".$operator. " " . ":$bind" ." ";
        return $this;
    }
    public function andWhereLike (string $column, $value) 
    {
        $this->bindValues[]= [$column => $value];
        $this->where[]= " AND ".$column." LIKE " . " :$column ";

        return $this;
    }
    public function andWhereILike (string $column, string $value) 
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        $this->where[]= " AND ".$column." ILIKE " . " :$column ";

        return $this;
    }

    public function orWhereLike (string $column, $value) 
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        $this->where[]= " OR ".$column." LIKE " . " :$column ";

        return $this;
    }
    public function orWhereILike (string $column, string $value) 
    {
        $bind = $this->cutBindColumn($column);

        $this->bindValues[]= [$bind => $value];

        $this->where[]= " OR ".$column." ILIKE " . " :$column " ;

        return $this;
    }

    protected function cutBindColumn(string $bindColumn) 
    {
        if(str_contains($bindColumn, ".")) 
        {
            $dotPosition = strpos($bindColumn, ".");
            //deve retornar toda a string apos o '.' para criar o bindColumn
            $bindColumn = substr($bindColumn, $dotPosition + 1);
            return $bindColumn;
        }
        return $bindColumn;
    }
}