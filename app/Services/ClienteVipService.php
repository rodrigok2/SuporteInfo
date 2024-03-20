<?php

namespace App\Services;

use App\Repositories\Local\VipRepository;
use App\Repositories\SUL\SisContratosRepository;
use App\Repositories\SUL\SisClientesRepository;
use App\Repositories\SUL\SisOsRepository;
use Illuminate\Support\Facades\Log;


class ClienteVipService
{
    public function VerificarNovasOs(){
        $vips = null;

        //verificar se a tabela vips possui registros
        $vipRepository = new VipRepository();
        $vips = $vipRepository->PesquisarVips();

        if(!$vips || empty($vips)){
            Log::critical('Falha ao consultar clientes vips no banco local', ['variavel_vips' => $vips]);
        }

        for($i = 0; $i < count($vips); $i++){
            $sisContratosRepository = new SisContratosRepository();
            $contratoCliente = $sisContratosRepository->ContratoPorId($vips[$i]['contrato_id']);

            if(!$contratoCliente || empty($contratoCliente)){
                Log::critical('Falha ao consultar contrato dos clientes no SUL', ['variavel_contratoCLiente' => $contratoCliente]);
            }

            $sisClientesRepository = new SisClientesRepository();
            $dadosCliente = $sisClientesRepository->ClientePorId($contratoCliente[0]['cliente_id']);

            if(!$dadosCliente || empty($dadosCliente)){
                Log::critical('Falha ao consultar dados dos clientes no SUL', ['variavel_dadosCliente' => $dadosCliente]);
            }

            $dadosVips[$i]['cliente_id'] = $contratoCliente[0]['cliente_id'];
            $dadosVips[$i]['razao_social'] = $dadosCliente[0]['razao_social'];
            $dadosVips[$i]['fantasia'] = $dadosCliente[0]['fantasia'];
            $dadosVips[$i]['cnpj'] = $dadosCliente[0]['cnpj'];
            $dadosVips[$i]['contrato_id'] = $vips[$i]['contrato_id'];
            $dadosVips[$i]['contrato_status'] = $contratoCliente[0]['contrato_status'];
            $dadosVips[$i]['ultima_os'] = $vips[$i]['os_id'];
            $dadosVips[$i]['vip_id'] = $vips[$i]['vip_id'];

            $sisOsRepository = new SisOsRepository();
            $osList = $sisOsRepository->NovasOSporContratoId($dadosVips[$i]['ultima_os'], $dadosVips[$i]['contrato_id']);

            if(!empty($osList[0])){
                $dadosVips[$i]['novas_os'] = $osList;
            }
            else{
                $dadosVips[$i]['novas_os'] = null;
            }
        }

        if(!$vips || !$contratoCliente || !$dadosCliente){
            return(null);
        }
        else{
            return($dadosVips);
        }
    }
}





