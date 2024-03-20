<?php

namespace App\Repositories\SUL;

use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class UsersGrupsRepository{
    function DepartamentoPorId($grupo_id){
        try{
            //Pesquisar o setor do usuário
            $setor = DB::connection('mysql_sul')->table("users_grups")
                ->select(
                    "users_grups.nome as grupo_descricao",
                    )
                ->where("users_grups.id", "=", $grupo_id)
                ->get();

            $setor = json_decode(json_encode($setor), true);

            return $setor;
        }catch(Exception $e){
            Log::critical('UsersGrupsRepository->DepartamentoPorId', [$e]);
            return false;
        }
    }
}
