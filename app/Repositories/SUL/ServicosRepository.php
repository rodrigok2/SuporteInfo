<?php

namespace App\Repositories\SUL;

use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class ServicosRepository{

    function ServicoPorId($servico_id){
        try{
            //Buscar o servico no SUL por id
            $servico = DB::connection('mysql_sul')->table("servicos")
                ->select(
                    "servicos.descricao as descricao_servico",
                    "servicos.subgrupo as subgrupo_id",
                    "servicos.nivel as nivel_servico",
                    "servicos.tempo as tempo_medio"
                )
                ->where("servicos.id", "=", $servico_id)
                ->get();

            //Converte o resultado em array
            $servico = json_decode(json_encode($servico), true);

            return $servico;
        }catch(Exception $e){
            Log::critical('ServicosRepository->ServicoPorId', [$e]);
            return false;
        }

    }

}
