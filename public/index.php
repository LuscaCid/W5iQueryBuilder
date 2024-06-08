<?php

use QueryBuilder\bootstrap\W5iQueryBuilder;

include "../vendor/autoload.php";

$resultsFromSipec = (new W5iQueryBuilder("receita_orcamentaria"))
     ->select(["id_fonterecurso"])
     ->whereIn("id_fonterecurso", (
         new W5iQueryBuilder("conta_entidade_fonte"))
         ->select(["id_fonterecurso"])
         ->where("id_contaentidade", "=" ,22)
         ->load(TRUE)
         )
     ->load();

var_dump($resultsFromSipec) ;

$resultsFromRelatorioLocal = (
    new W5iQueryBuilder("relatorio AS r", "relatorio_local")
    )->select([
        "r.id_relatorio", 
        "r.nm_relatorio", 
        "r.nm_titulo",
        "rf.nm_relatorio_filtro",
        "rf.nm_titulo",
        "rq.nm_query",
        "rq.ds_query",
    ])
    ->innerJoin("relatorio_filtro AS rf", "r.id_relatorio", "=", "rf.id_relatorio")
    ->innerJoin("relatorio_query AS rq", "r.id_relatorio", "=", "rq.id_relatorio")
    ->load();

var_dump($resultsFromRelatorioLocal);