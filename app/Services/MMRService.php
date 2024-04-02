<?php

namespace App\Services;

use App\Models\Servico;
use App\Repositories\Local\VipRepository;
use App\Repositories\SUL\SisClientesRepository;
use App\Repositories\SUL\SisOsRepository;
use App\Repositories\SUL\ServicosRepository;
use Illuminate\Support\Facades\Log;


class MMRService
{
    public function calcularMmr($tecnicos, $data_inicial, $data_final){
        $data_inicial = $data_inicial." 00:00:00";
        $data_final = $data_final." 23:59:59";

        $osRepository = new SisOsRepository();
        $list = $osRepository->OsFechadasPorPeriodo($data_inicial, $data_final);

        $servicoRepository = new ServicosRepository();
        for( $i = 0; $i < count($list); $i++ ){
            if($list[$i]['servico_id'] == null){
                array_splice($list, $i, 1);
                $i--;
            }
            else{
                $servico = $servicoRepository->ServicoPorId($list[$i]['servico_id']);
                if($servico[0]['nivel_servico'] === 1){
                    $list[$i]['nivel_servico'] = $servico[0]['nivel_servico'];
                }
                elseif($servico[0]['nivel_servico'] === 2){
                    $list[$i]['nivel_servico'] = 3.13;
                }
                elseif($servico[0]['nivel_servico'] === 3){
                    $list[$i]['nivel_servico'] = 8.25;
                }
                $list[$i]['tempo_medio'] = $servico[0]['tempo_medio'];
            }
        }

        for( $i = 0; $i < count($tecnicos); $i++ ){
            $tecnicos[$i]['mmr'] = 0;
            $tecnicos[$i]['qtde_os'] = 0;
            $tecnicos[$i]['total_n1'] = 0;
            $tecnicos[$i]['total_n2'] = 0;
            $tecnicos[$i]['total_n3'] = 0;
            //dd($tecnicos[$i]['tecnico_id']);
            for( $j = 0; $j < count($list); $j++ ){
                if($tecnicos[$i]['tecnico_id'] === $list[$j]['tecnico_id']){
                    $nivel = $list[$j]['nivel_servico'];
                    if($list[$j]['tempo'] <= ($list[$j]['tempo_medio']) * 0.7){
                        $nivel = $nivel + ($nivel * 0.20);
                    }
                    elseif($list[$j]['tempo'] <= ($list[$j]['tempo_medio']) * 0.95){
                        $nivel = $nivel + ($nivel * 0.10);
                    }
                    elseif(($list[$j]['tempo'] >= ($list[$j]['tempo_medio']) * 1.05) && ($list[$j]['tempo'] < ($list[$j]['tempo_medio']) * 1.30)){
                        $nivel = $nivel - ($nivel * 0.10);
                    }
                    elseif($list[$j]['tempo'] >= ($list[$j]['tempo_medio']) * 1.30){
                        $nivel = $nivel - ($nivel * 0.20);
                    }
                    $tecnicos[$i]['mmr'] += $nivel;
                    $tecnicos[$i]['qtde_os'] += 1;
                    if($nivel < 2){
                        $tecnicos[$i]['total_n1']++;
                    }
                    elseif($nivel < 5){
                        $tecnicos[$i]['total_n2']++;
                    }
                    elseif($nivel < 12){
                        $tecnicos[$i]['total_n3']++;
                    }
                }
            }
        }
        return ($tecnicos);
    }
}
