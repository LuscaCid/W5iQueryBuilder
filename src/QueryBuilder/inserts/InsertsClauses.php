<?php
use Adianti\Database\TRecord;

class InsertsClauses 
{
    public function __construct()
    {
        
    }
    public function insertMany(array $collection, mixed $model, string $primaryKey = NULL, string $foreignKey = NULL) 
    {
        if ($model instanceof TRecord && is_array($collection))
        {
            self::insertArr($model, $collection,$primaryKey, $foreignKey);
        }
        else 
        {

        }
    }

    //SE EXISTIR ALGUMA RELACAO ENTRE TABELAS, DENTRO DO ARRAY DEVE CONTER OS OBJETOS CONTENDO DENTRO DE CADA, O
    private static function insertArr($model, $array, string $primaryKey = NULL, string $foreignKey = NULL) 
    {
        foreach ($array as $item) 
        {
            $modelInstance = new $model($item::PRIMARYKEY);
            $modelInstance->fromArray((array) $item);

            $modelInstance->store();
            
        }
    }
}