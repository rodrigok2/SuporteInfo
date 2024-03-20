<?php

namespace App\Repositories\SUL;

use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class SisSquadsRepository{

    function PesquisarSquads(){
        try{
            //Buscar o contrato do cliente no SUL por id
            $squads = DB::connection('mysql_sul')->table("sis_squads")
            ->select(
                "sis_squads.id as squad_id",
                "sis_squads.nome as squad",
            )
            ->get();

            //Converte o resultado em array
            $squads = json_decode(json_encode($squads), true);

            //retorna o array com o contrato do SUL
            return $squads;
        }catch(Exception $e){
            Log::critical('SisSquadsRepository->PesquisarSquads', [$e]);
            return false;
        }
    }

    function SquadPorId($squad_id){
        try{
            //Buscar o contrato do cliente no SUL por id
            $squad = DB::connection('mysql_sul')->table("sis_squads")
            ->select(
                "sis_squads.id as squad_id",
                "sis_squads.nome as squad",
            )
            ->where("sis_squads.id", "=", $squad_id)
            ->get();

            //Converte o resultado em array
            $squad = json_decode(json_encode($squad), true);

            //retorna o array com o contrato do SUL
            return $squad;
        }catch(Exception $e){
            Log::critical('SisSquadsRepository->SquadPorId', [$e]);
            return false;
        }
    }
}
