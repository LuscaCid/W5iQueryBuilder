# W5IQueryBuilder

## Summary
Um simples query builder para padronizar a montagem de queries, deixando o código mais organizado e limpo, e permitindo que as consultas sejam feitas de forma mais padronizada.

## Author
Lucas Felipe Lima Cid  
<lucasfelipaaa@gmail.com>

## Created
04/06/2024

## Classe W5IQueryBuilder

### Propriedades
- `private array $placeholderValues`: Array para armazenar valores de placeholders.
- `private array $bindValues`: Array para armazenar valores de bind.
- `private string $query`: String para armazenar a query SQL.
- `private string $tableName`: Nome da tabela para a consulta.
- `private string $transactionUnit`: Unidade de transação para o TTransaction.

### Métodos
#### `__construct(string $tableName, string $transactionUnit)`
Construtor da classe que inicializa o nome da tabela e a unidade de transação.

#### `__call($name, $arguments)`
Implementação do método mágico `__call` para tratar métodos dinâmicos como `JOIN`.

#### `verifyIfAlreadyHasClauseWhere(): bool`
Verifica se a cláusula WHERE já está presente na query.

#### `getQuery()`
Retorna a query construída até o momento.

#### `selectCount()`
Constrói uma query para contar os registros da tabela.

#### `select(array $columns)`
Constrói uma query de seleção com as colunas especificadas.

#### `where(string $column, string $operator, string $value)`
Adiciona uma cláusula WHERE à query.

#### `orderBy(string $column, string $order)`
Adiciona uma cláusula ORDER BY à query.

#### `offset(int $offset = 0)`
Adiciona uma cláusula OFFSET à query.

#### `limit(int $limit = 5)`
Adiciona uma cláusula LIMIT à query.

#### `andWhere(string $column, string $operator, string $value)`
Adiciona uma cláusula AND WHERE à query.

#### `orWhere(string $column, string $operator, string $value)`
Adiciona uma cláusula OR WHERE à query.

#### `innerJoin(mixed $table, string $leftSide, string $operator, string $rightSide)`
Adiciona uma cláusula INNER JOIN à query.

#### `leftJoin(string $table, string $leftSide, string $operator, string $rightSide)`
Adiciona uma cláusula LEFT JOIN à query.

#### `whereIn(string $column, array $items)`
Adiciona uma cláusula WHERE IN à query.

#### `andWhereIn(string $column, array $items)`
Adiciona uma cláusula AND WHERE IN à query.

#### `whereNotIn(string $column, array $items)`
Adiciona uma cláusula WHERE NOT IN à query.

#### `rightJoin(mixed $table, string $leftSide, string $operator, string $rightSide)`
Adiciona uma cláusula RIGHT JOIN à query.

#### `andWhereLike(string $column, $value)`
Adiciona uma cláusula AND WHERE LIKE à query.

#### `andWhereILike(string $column, string $value)`
Adiciona uma cláusula AND WHERE ILIKE à query.

#### `orWhereLike(string $column, $value)`
Adiciona uma cláusula OR WHERE LIKE à query.

#### `orWhereILike(string $column, string $value)`
Adiciona uma cláusula OR WHERE ILIKE à query.

#### `cutBindColumn(string $bindColumn)`
Remove o prefixo da coluna para criar um nome de bind.

#### `first()`
Executa a query e retorna o primeiro resultado como um objeto.

#### `load($isValueable = false)`
Executa a query e retorna todos os resultados. Se `$isValueable` for true, retorna apenas os valores.

#### `getArrValues(array $array)`
"Achata" um array multidimensional em um array unidimensional contendo apenas os valores.

## Exemplo de Uso

```php
<?php
//redução de services, promovendo uma estrutura mais enxuta e ainda podendo realizar subQueries
public static function getIdsFromIdFonteRecurso($idContaEntidade) 
{
    return (new W5IQueryBuilder("receita_orcamentaria", "unit_sipec"))
    ->select(["id_fonterecurso"])
    ->whereIn("id_fonterecurso", (
        new W5IQueryBuilder("conta_entidade_fonte", "unit_sipec"))
        ->select(["id_fonterecurso"])
        ->where("id_contaentidade", "=", $idContaEntidade)
        ->load(TRUE)
        )
    ->load();
}
````

## Evitando sql injection...

###`vai fazer o bind dos valores por baixo dos panos, abaixo um exemplo disto acontecendo`

````php
/**
 * @summary : Adiciona clausula AND para fazer mais uma comparacao na query
 * @author : Lucas Cid <lucasfelipaaa@gmail.com>
 * @param : string $column : Coluna que vai ser comparada.
 * @param : string $operator : operador para realizar a comparacao.
 * @param : string $value : valor a ser comparado, mas não diretamente, mas sofendo bind posteriormente. 
 * @return : a propria classe...
 * 
*/
public function andWhere(string $column, string $operator, string $value)  
{
    $bind = $this->cutBindColumn($column);

    //onde adiciona a bind e o valor correspondente no momento da insercao do where
    $this->bindValues[]= [$bind => $value];

    $this->query.= " AND " .$column. " ".$operator. " ". " :$bind ";
    return $this;
}
````
## Momento em que os valores sao relacionados aos seus respectivos "binds"

````php

$stmt = $pdo->prepare($this->query);
if(isset($this->bindValues) && is_array($this->bindValues) && !empty($this->bindValues)) 
{
    //adicionando os valores aos seus respectivos binds dentro do codigo sql gerado.
    foreach($this->bindValues as $bind) 
    {   
        $key = array_key_first($bind);
        $bindValue = $bind[$key];
        $stmt->bindValue(":$key", $bindValue, is_numeric($bindValue) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
}
$objects = $stmt->fetchObject(PDO::FETCH_OBJ);
````

