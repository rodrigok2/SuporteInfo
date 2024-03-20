<?php

namespace App\Repositories\SUL;

use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class ServicosGruposRepository{

    function GrupoPorId($grupo_id){
        try{
            //Buscar o grupo do servico no SUL por id
            $grupo = DB::connection('mysql_sul')->table("servicos_grupos")
                ->select("servicos_grupos.descricao as descricao_grupo")
                ->where("servicos_grupos.id", "=", $grupo_id)
                ->get();


            //Converte o resultado em array
            $grupo = json_decode(json_encode($grupo), true);

            return $grupo;
        }catch(Exception $e){
            Log::critical('ServicosGruposRepository->GrupoPorId', [$e]);
            return false;
        }
    }
}
