<?php

use QueryBuilder\bootstrap\QueryBuilder;
use QueryBuilder\bootstrap\W5iQueryBuilder;

include "../vendor/autoload.php";

$results = (new W5iQueryBuilder("receita_orcamentaria"))
    ->select(["id_fonterecurso"])
    ->whereIn("id_fonterecurso", (
        new W5iQueryBuilder("conta_entidade_fonte"))
        ->select(["id_fonterecurso"])
        ->where("id_contaentidade", "=", 22)
        ->load(TRUE)
        )
    ->load();



var_dump($results) ;
