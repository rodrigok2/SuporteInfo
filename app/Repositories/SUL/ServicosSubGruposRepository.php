<?php

namespace App\Repositories\SUL;

use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class ServicosSubgruposRepository{

    function SubGruposPorId($subgrupo_id){
        try{
            //Buscar o subgrupo do servico no SUL por id
            $subgrupo = DB::connection('mysql_sul')->table("servicos_subgrupos")
                ->select("servicos_subgrupos.descricao as descricao_subgrupo", "servicos_subgrupos.grupo as grupo_id")
                ->where("servicos_subgrupos.id", "=", $subgrupo_id)
                ->get();


            //Converte o resultado em array
            $subgrupo = json_decode(json_encode($subgrupo), true);

            return $subgrupo;
        }catch(Exception $e){
            Log::critical('ServicosSubgruposRepository->SubGruposPorId', [$e]);
            return false;
        }

    }

}
