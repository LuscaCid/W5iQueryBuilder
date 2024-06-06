<?php

use QueryBuilder\bootstrap\W5IQueryBuilder;

include "../vendor/autoload.php";

$results = (new W5IQueryBuilder("receita_orcamentaria", "unit_sipec"))
    ->select(["id_fonterecurso"])
    ->whereIn("id_fonterecurso", (
        new W5IQueryBuilder("conta_entidade_fonte", "unit_sipec"))
        ->select(["id_fonterecurso"])
        ->where("id_contaentidade", "=", 22)
        ->load(TRUE)
        )
    ->load();

var_dump($results) ;