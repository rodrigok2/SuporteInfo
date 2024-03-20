<?php

namespace App\Repositories\SUL;

use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class SisPrioridadesRepository{

    function PesquisarPrioridades(){
        try{
        //Busca a tabela de prioridades no SUL
        $prioridades = DB::connection('mysql_sul')->table("sis_prioridades")
        ->select(
            "sis_prioridades.id as prioridade_id",
            "sis_prioridades.descricao as prioridade_descricao"
        )
        ->get();

        //Converte o resultado em array
        $prioridades = json_decode(json_encode($prioridades), true);

        //retornar o array de prioridades
        return $prioridades;
        }catch(Exception $e){
            Log::critical('SisPrioridadesRepository->PesquisarPrioridades', [$e]);
            return false;
        }
    }

    function PrioridadePorId($prioridade_id){
        try{
            //Buscar o servico no SUL por id
            $prioridade = DB::connection('mysql_sul')->table("sis_prioridades")
                ->select("sis_prioridades.descricao as prioridade_os")
                ->where("sis_prioridades.id", "=", $prioridade_id)
                ->get();

            //Converte o resultado em array
            $prioridade = json_decode(json_encode($prioridade), true);

            return $prioridade;
        }catch(Exception $e){
            Log::critical('SisPrioridadesRepository->PrioridadePorId', [$e]);
            return false;
        }

    }
}
