<?php
namespace QueryBuilder\helpers;
trait W5IQueryBuilderHelpers 
{
    protected function getArrValues(array $array) 
    {
        if(is_array($array) && !empty($array))
        {
            $arrWithOnlyValues = [];
            foreach ($array as $item) 
            {
                $keys = array_keys($item);

                foreach ($keys as $key)
                {
                    $value = $item[$key];
                    $arrWithOnlyValues[] = $value;
                }
            }
            return $arrWithOnlyValues;
        }
    }
    protected function verifyIfAlreadyHasClauseWhere($query) : bool
    {
        if(str_contains(strtoupper($query), "WHERE")) 
        {
            return true;
        };
        return false;
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