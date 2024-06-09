# W5IQueryBuilder

## Instalacao

Para usar num projeto adiantiFramework: Basta apenas copiar a pasta QueryBuilder alocar no util do projeto e então, se divirta!  

## Summary
Um simples query builder para padronizar a montagem de queries, deixando o código mais organizado e limpo, e permitindo que as consultas sejam feitas de forma mais padronizada, podendo tanto ser usado em ambiente AdiantiFramework quanto num projeto qualquer, apenas acessando src/config/DataSource.php e passar o boolean 'true' para a propriedade "isAdiantiFrameworkProject" caso seja e 'false' caso não.


## Autor
Lucas Felipe Lima Cid  
<lucasfelipaaa@gmail.com>

## Criado em: 04/06/2024


W5iQueryBuilder
================

Uma classe de construtor de consultas poderosa para construir e executar consultas de banco de dados.

### Construtor

`__construct(mixed $tables = NULL, string $otherUnit = NULL)`

* `$tables`: Um array ou string de nomes de tabelas para consultar. Pode ser nulo para definir tabelas posteriormente usando o método `from`.
* `$otherUnit`: Uma string opcional para especificar uma unidade de banco de dados diferente para usar na consulta.

### Métodos

#### SELECT

`select(array $columns)`

* `$columns`: Um array de nomes de colunas para selecionar.
* Retorna: A instância atual do construtor de consultas.

Seleciona as colunas especificadas da(s) tabela(s).

`selectCount(array $columns = NULL, string $alias = NULL)`

* `$columns`: Um array opcional de nomes de colunas para contar.
* `$alias`: Uma string opcional para aliasar a coluna de contagem.
* Retorna: A instância atual do construtor de consultas.

Seleciona a contagem das colunas especificadas da(s) tabela(s).

#### FROM

`from(array $tables)`

* `$tables`: Um array de nomes de tabelas para consultar.
* Retorna: A instância atual do construtor de consultas.

Especifica a(s) tabela(s) para consultar.

#### WHERE

`where(string $column, string $operator, string $value)`

* `$column`: O nome da coluna para filtrar.
* `$operator`: O operador a usar para o filtro (ex.: `=`, `>`, `<`, etc.).
* `$value`: O valor para filtrar.
* Retorna: A instância atual do construtor de consultas.

Adiciona uma cláusula `where` à consulta.

`whereBetween(string $column, string $start, string $end)`

* `$column`: O nome da coluna para filtrar.
* `$start`: O valor de início do intervalo.
* `$end`: O valor de fim do intervalo.
* Retorna: A instância atual do construtor de consultas.

Adiciona uma cláusula `where` à consulta que filtra em um intervalo de valores.

`whereIn(string $column, array $values)`

* `$column`: O nome da coluna para filtrar.
* `$values`: Um array de valores para filtrar.
* Retorna: A instância atual do construtor de consultas.

Adiciona uma cláusula `where` à consulta que filtra em um array de valores.

`andWhere(string $column, string $operator, string $value)`

* `$column`: O nome da coluna para filtrar.
* `$operator`: O operador a usar para o filtro (ex.: `=`, `>`, `<`, etc.).
* `$value`: O valor para filtrar.
* Retorna: A instância atual do construtor de consultas.

Adiciona uma cláusula `and where` à consulta.

`orWhere(string $column, string $operator, string $value)`

* `$column`: O nome da coluna para filtrar.
* `$operator`: O operador a usar para o filtro (ex.: `=`, `>`, `<`, etc.).
* `$value`: O valor para filtrar.
* Retorna: A instância atual do construtor de consultas.

Adiciona uma cláusula `or where` à consulta.

#### JOINS

`innerJoin(string $table, string $rightSide, string $operator, string $leftSide)`

* `$table`: A tabela para juntar.
* `$rightSide`: O lado direito da junção.
* `$operator`: O operador a usar para a junção (ex.: `=`, `>`, `<`, etc.).
* `$leftSide`: O lado esquerdo da junção.
* Retorna: A instância atual do construtor de consultas.

Adiciona uma junção interna à consulta.

`leftJoin(string $table, string $rightSide, string $operator, string $leftSide)`

* `$table`: A tabela para juntar.
* `$rightSide`: O lado direito da junção.
* `$operator`: O operador a usar para a junção (ex.: `=`, `>`, `<`, etc.).
* `$leftSide`: O lado esquerdo da junção.
* Retorna: A instância atual do construtor de consultas.

Adiciona uma junção esquerda à consulta.

`rightJoin(string $table, string $rightSide, string $operator, string $leftSide)`

* `$table`: A tabela para juntar.
* `$rightSide`: O lado direito da junção.
* `$operator`: O operador a usar para a junção (ex.: `=`, `>`, `<`, etc.).
* `$leftSide`: O lado esquerdo da junção.
* Retorna: A instância atual do construtor de consultas.

Adiciona uma junção direita à consulta.
