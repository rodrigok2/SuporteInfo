<?php

namespace App\Repositories\SUL;

use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class SisContratosRepository{

    function ContratoPorId($contrato_id){
        try{
            //Buscar o contrato do cliente no SUL por id
            $contrato = DB::connection('mysql_sul')->table("sis_contratos")
            ->select(
                    "sis_contratos.cliente as cliente_id",
                    "sis_contratos.status as contrato_status",
                    "sis_contratos.lic_sistema as sistema_id",
            )
            ->where("sis_contratos.id", "=", $contrato_id)
            ->get();

            //Converte o resultado em array
            $contrato = json_decode(json_encode($contrato), true);

            //retorna o array com o contrato do SUL
            return $contrato;
        }catch(Exception $e){
            Log::critical('SisContratosRepository->ContratoPorId', [$e]);
            return false;
        }
    }

    function ContratoPorClienteId($cliente_id){
        try{
            //Buscar o contrato do cliente no SUL por id
            $contrato = DB::connection('mysql_sul')->table("sis_contratos")
            ->select(
                "sis_contratos.id as contrato_id",
                "sis_contratos.cliente as cliente_id",
                "sis_contratos.status as contrato_status",
                "sis_contratos.lic_sistema as sistema_id",
            )
            ->where("sis_contratos.cliente", "=", $cliente_id)
            ->get();

            //Converte o resultado em array
            $contrato = json_decode(json_encode($contrato), true);

            //retorna o array com os contratos do cliente
            return $contrato;
        }catch(Exception $e){
            Log::critical('SisContratosRepository->ContratoPorClienteId', [$e]);
            return false;
        }
    }

    function ContratoAtivoPorId($contrato_id){
        try{
            //Buscar o contrato do cliente no SUL por id
            $contrato = DB::connection('mysql_sul')->table("sis_contratos")
            ->select(
                    "sis_contratos.cliente as cliente_id",
                    "sis_contratos.status as contrato_status",
                    "sis_contratos.lic_sistema as sistema_id",
            )
            ->where("sis_contratos.id", "=", $contrato_id)
            ->where("sis_contratos.status", "=", 'Ativo Mensal')
            ->get();

            //Converte o resultado em array
            $contrato = json_decode(json_encode($contrato), true);

            //retorna o array com o contrato do SUL
            return $contrato;
        }catch(Exception $e){
            Log::critical('SisContratosRepository->ContratoPorId', [$e]);
            return false;
        }
    }

    function ContratoAtivoPorClienteId($cliente_id){
        try{
            //Buscar o contrato do cliente no SUL por id
            $contrato = DB::connection('mysql_sul')->table("sis_contratos")
            ->select(
                "sis_contratos.id as contrato_id",
                "sis_contratos.cliente as cliente_id",
                "sis_contratos.status as contrato_status",
                "sis_contratos.lic_sistema as sistema_id",
            )
            ->where("sis_contratos.cliente", "=", $cliente_id)
            ->where("sis_contratos.status", "=", 'Ativo Mensal')
            ->get();

            //Converte o resultado em array
            $contrato = json_decode(json_encode($contrato), true);

            //retorna o array com os contratos do cliente
            return $contrato;
        }catch(Exception $e){
            Log::critical('SisContratosRepository->ContratoAtivoPorClienteId', [$e]);
            return false;
        }
    }
}
