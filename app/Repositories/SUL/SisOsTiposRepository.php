<?php

namespace App\Repositories\SUL;

use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class SisOsTiposRepository{


    function PesquisarTiposOs(){
        try{
            //Pesquisa os tipos do O.S. do SUL ordenado pela descrição
            $classificacoes = DB::connection('mysql_sul')->table("sis_os_tipos")
            ->select(
                "sis_os_tipos.id as classificacao_id",
                "sis_os_tipos.nome as classificacao_os",
                )
            ->orderBy("classificacao_os", "asc")
            ->get();

            //retorna as classificacoes
            return $classificacoes;
        }catch(Exception $e){
            Log::critical('SisOsTiposRepository->PesquisarTiposOs', [$e]);
            return false;
        }
    }

    function TipoOsPorId($classificacao_id){
        try{
            //Pesquisa a classificacao da OS
            $classificacao = DB::connection('mysql_sul')->table("sis_os_tipos")
                ->select(
                    "sis_os_tipos.nome as classificacao_os",
                    )
                ->where("sis_os_tipos.id", "=", $classificacao_id)
                ->get();

            $classificacao = json_decode(json_encode($classificacao), true);

            return $classificacao;
        }catch(Exception $e){
            Log::critical('SisOsTiposRepository->TipoOsPorId', [$e]);
            return false;
        }
    }
}
