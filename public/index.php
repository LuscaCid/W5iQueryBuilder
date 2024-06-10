<?php

use QueryBuilder\bootstrap\W5iQueryBuilder;

include "../vendor/autoload.php";

$resultsFromSipec = (new W5iQueryBuilder("receita_orcamentaria", "sipec"))
    ->select(["id_fonterecurso"])
    ->whereIn("id_fonterecurso", (
        //construção de subquerie dentro de clausula WHERE IN
       new W5iQueryBuilder("conta_entidade_fonte"))
       ->select(["id_fonterecurso"])
       ->where("id_contaentidade",22)
       //dizendo ao metodo load que ele deve retornar apenas os valores e nao objetos para conseguir inserir na clausula IN
       ->load(TRUE)
       )
   ->load();
var_dump($resultsFromSipec);

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
    ->limit(4)
    ->offset(0)
    ->load();
var_dump($resultsFromRelatorioLocal);

$test = (
    new W5iQueryBuilder("receita_orcamentaria as ro", "sipec")
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
    //sobrecarga de metodo com o __call 
    ->where('ro.id_criterioreceita', '<>', 2)
    ->load();
var_dump($test);

// $countTest = (
//     new W5IQueryBuilder("relatorio", "relatorio_local")
// )
// ->selectCount(["*"])
// ->first();

// var_dump($countTest);

function service () 
{
    return (
        new W5iQueryBuilder(["setor as s"], "patrimonio_dados")
        )
        ->select([
            "s.cd_setor",
            "s.id_setor",
            "s.nm_setor",
            "s.nm_complemento",
            "s.nm_bairro",
            "s.nm_localidade",
            "s.nm_uf",
            "m.nm_membro"
        ])
        ->leftJoin("membro as m", "m.id_membro", "=", "s.id_membroresponsavel")
        //POSSIBILIDADE DE "ERRAR. . ."
        ->andWhereILike("s.cd_setor", 'set001')
        ->where("s.fl_desativado", false)
        ->load();
}

$data = service();

var_dump($data);