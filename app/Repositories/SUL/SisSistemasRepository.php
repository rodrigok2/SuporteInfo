<?php

namespace App\Repositories\SUL;

use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class SisSistemasRepository{

    function PesquisarSistemas(){
        try{
            //Buscar o contrato do cliente no SUL por id
            $sistemas = DB::connection('mysql_sul')->table("sis_sistemas")
            ->select(
                "sis_sistemas.id as sistema_id",
                "sis_sistemas.nome as sistema",
            )
            ->where("sis_sistemas.ativo", "=", 1)
            ->get();

            $sistemas = json_decode(json_encode($sistemas), true);

            //retorna o array com o contrato do SUL
            return $sistemas;
        }catch(Exception $e){
            Log::critical('SisSistemasRepository->PesquisarSistemas', [$e]);
            return false;
        }
    }

    function SistemaPorId($sistema_id){
        try{
            //Buscar o contrato do cliente no SUL por id
            $contrato = DB::connection('mysql_sul')->table("sis_sistemas")
            ->select(
                    "sis_sistemas.nome as sistema",
            )
            ->where("sis_sistemas.id", "=", $sistema_id)
            ->get();

            //Converte o resultado em array
            $contrato = json_decode(json_encode($contrato), true);

            //retorna o array com o contrato do SUL
            return $contrato;
        }catch(Exception $e){
            Log::critical('SisSistemasRepository->SistemaPorId', [$e]);
            return false;
        }
    }
}
