<?php

use QueryBuilder\bootstrap\W5iQueryBuilder;

include "../vendor/autoload.php";

// $resultsFromSipec = (new W5iQueryBuilder("receita_orcamentaria"))
//      ->select(["id_fonterecurso"])
//      ->whereIn("id_fonterecurso", (
//          new W5iQueryBuilder("conta_entidade_fonte"))
//          ->select(["id_fonterecurso"])
//          ->where("id_contaentidade", "=" ,22)
//          ->load(TRUE)
//          )
//      ->load();

// var_dump($resultsFromSipec) ;

// $resultsFromRelatorioLocal = (
//     new W5iQueryBuilder("relatorio AS r", "relatorio_local")
//     )->select([
//         "r.id_relatorio", 
//         "r.nm_relatorio", 
//         "r.nm_titulo",
//         "rf.nm_relatorio_filtro",
//         "rf.nm_titulo",
//         "rq.nm_query",
//         "rq.ds_query",
//     ])
//     ->innerJoin("relatorio_filtro AS rf", "r.id_relatorio", "=", "rf.id_relatorio")
//     ->innerJoin("relatorio_query AS rq", "r.id_relatorio", "=", "rq.id_relatorio")
//     ->load();

// var_dump($resultsFromRelatorioLocal);

$test = (
    new W5iQueryBuilder("receita_orcamentaria as ro")
    )->select([
        'ro.id_receitaorcamentaria',
        'ro.cd_receitaorcamentaria',
        'crr.ds_criterioreceita',
        'ro.id_previsaoreceita',
        'nr.cd_naturezareceita',
        'nr.ds_receita',
        'ro.id_criterioreceita',
        'ro.vl_receita',
        'pcp.vl_contribuicao'
    ])
    ->innerJoin('criterio_registro_receita as crr', 'ro.id_criterioreceita', '=', 'crr.id_criterioreceita')
    ->innerJoin('previsao_receita as pr', 'ro.id_previsaoreceita', '=', 'pr.id_previsaoreceita')
    ->innerJoin('natureza_receita as nr', 'pr.id_naturezareceita', '=', 'nr.id_naturezareceita')
    ->innerJoin('receita_cota_participacao as rcp', 'nr.id_naturezareceita', '=', 'rcp.id_naturezareceita')
    ->innerJoin('percentual_cota_participacao as pcp', 'rcp.id_cotaparticipacao', '=', 'pcp.id_cotaparticipacao')
    ->where('ro.id_previsaoreceita', 53)
    ->where('ro.id_criterioreceita', '<>', 2)
    ->load();


var_dump($test);