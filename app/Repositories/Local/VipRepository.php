<?php

namespace App\Repositories\Local;

use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Vip;
use Illuminate\Support\Facades\Log;

class VipRepository{

    function PesquisarVips(){
        try{
            $vips = DB::connection('mysql')->table("vip")
                ->select(
                    "vip.id as vip_id",
                    "vip.contrato_id_sul as contrato_id",
                    "vip.ultima_os_notificada as os_id",
                    )
                ->get();

            //Converte para array
            $vips = json_decode(json_encode($vips), true);

            return $vips;
        }catch(Exception $e){
            Log::critical('VipRepository->PesquisarVips', [$e]);
            return false;
        }
    }

    function CadastrarVip($dadosRequest){
        try{
            $vip = new Vip();
            $vip->contrato_id_sul = $dadosRequest['contrato_id'];
            $vip->ultima_os_notificada = $dadosRequest['os_id'];
            $vip->save();

            return true;
        }catch(Exception $e){
            Log::critical('VipRepository->CadastrarVip', [$e]);
            return false;
        }
    }

    function AtualizarUltimaOs($os_id, $vip_id){
        try{
            $vip = Vip::find($vip_id);
            $vip->ultima_os_notificada = intval($os_id);
            $vip->update();

            return true;
        }catch(Exception $e){
            Log::critical('VipRepository->AtualizarUltimaOs', [$e]);
            return false;
        }
    }

    function ExcluirVip($vip_id){
        try{
            $vip = Vip::find($vip_id);
            $vip->delete();

            return true;
        }catch(Exception $e){
            Log::critical('VipRepository->ExcluirVip', [$e]);
            return false;
        }
    }
}
