<?php

use QueryBuilder\bootstrap\QueryBuilder;

include "../vendor/autoload.php";

$results = (new QueryBuilder("receita_orcamentaria"))
    ->select(["id_fonterecurso"])
    ->whereIn("id_fonterecurso", (
        new QueryBuilder("conta_entidade_fonte"))
        ->select(["id_fonterecurso"])
        ->where("id_contaentidade", "=", 22)
        ->load(TRUE)
        )
    ->load();



var_dump($results) ;

$newInstance = (new QueryBuilder())->select([]);