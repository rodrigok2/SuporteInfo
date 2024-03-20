<?php

namespace App\Repositories\SUL;

use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class SisTreinamentoUsuarioRepository{

    function SolicitantePorId($solicitante_id){
        try{
            //Buscar o servico no SUL por id
            $solicitante = DB::connection('mysql_sul')->table("sis_treinamento_usuario")
                ->select(
                    "sis_treinamento_usuario.nome as solicitante_nome",
                    "sis_treinamento_usuario.cpf as solicitante_cpf",
                    "sis_treinamento_usuario.email as solicitante_email",
                    "sis_treinamento_usuario.telefone as solicitante_telefone",
                )
                ->where("sis_treinamento_usuario.id", "=", $solicitante_id)
                ->get();

            //Converte o resultado em array
            $solicitante = json_decode(json_encode($solicitante), true);

            return $solicitante;
        }catch(Exception $e){
            Log::critical('SisTreinamentoUsuarioRepository->SolicitantePorId', [$e]);
            return false;
        }
    }
}
