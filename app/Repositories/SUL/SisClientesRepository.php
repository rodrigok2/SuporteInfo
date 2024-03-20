<?php

namespace App\Repositories\SUL;

use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class SisClientesRepository{

    function ClientePorId($cliente_id){
        try{
            //Buscar o cliente no SUL por id
            $cliente = DB::connection('mysql_sul')->table("sis_clientes")
            ->select(
                "sis_clientes.nome as razao_social",
                "sis_clientes.fantasia as fantasia",
                "sis_clientes.cpf as cnpj"
            )
            ->where("sis_clientes.id", "=", $cliente_id)
            ->get();

            //Converte o resultado em array
            $cliente = json_decode(json_encode($cliente), true);

            return $cliente;
        }catch(Exception $e){
            Log::critical('SisCLientesRepository->ClientePorId', [$e]);
            return false;
        }
    }

    function ClientePorCPNJ($cnpj){
        try{
            //Buscar o cliente no SUL por id
            $cliente = DB::connection('mysql_sul')->table("sis_clientes")
            ->select(
                "sis_clientes.id as cliente_id",
                "sis_clientes.nome as razao_social",
                "sis_clientes.fantasia as fantasia",
                "sis_clientes.cpf as cnpj"
            )
            ->where("sis_clientes.cpf", "=", $cnpj)
            ->get();

            //Converte o resultado em array
            $cliente = json_decode(json_encode($cliente), true);

            return $cliente;
        }catch(Exception $e){
            Log::critical('SisCLientesRepository->ClientePorCPNJ', [$e]);
            return false;
        }
    }

    function ClientePorRazaoSocial($razao_social){
        try{
            //Buscar o cliente no SUL por id
            $cliente = DB::connection('mysql_sul')->table("sis_clientes")
            ->select(
                "sis_clientes.id as cliente_id",
                "sis_clientes.nome as razao_social",
                "sis_clientes.fantasia as fantasia",
                "sis_clientes.cpf as cnpj"
            )
            ->where("sis_clientes.nome", "like", '%'.$razao_social.'%')
            ->get();

            //Converte o resultado em array
            $cliente = json_decode(json_encode($cliente), true);

            return $cliente;
        }catch(Exception $e){
            Log::critical('SisCLientesRepository->ClientePorRazaoSocial', [$e]);
            return false;
        }
    }

    function ClientePorFantasia($fantasia){
        try{
            //Buscar o cliente no SUL por id
            $cliente = DB::connection('mysql_sul')->table("sis_clientes")
            ->select(
                "sis_clientes.id as cliente_id",
                "sis_clientes.nome as razao_social",
                "sis_clientes.fantasia as fantasia",
                "sis_clientes.cpf as cnpj"
            )
            ->where("sis_clientes.fantasia", "like", '%'.$fantasia.'%')
            ->get();

            //Converte o resultado em array
            $cliente = json_decode(json_encode($cliente), true);

            return $cliente;
        }catch(Exception $e){
            Log::critical('SisCLientesRepository->ClientePorFantasia', [$e]);
            return false;
        }
    }
}
