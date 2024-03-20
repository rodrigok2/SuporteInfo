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
    public function calcularMmr(){
        $primeiroDiaMesAtual = date('Y-m-01');
        $today = date("Y-m-d");

        $data_inicial = $primeiroDiaMesAtual." 00:00:00";
        $data_final = $today." 23:59:59";

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
                    $list[$i]['nivel_servico'] = 2.50;
                }
                elseif($servico[0]['nivel_servico'] === 3){
                    $list[$i]['nivel_servico'] = 5;
                }
                $list[$i]['tempo_medio'] = $servico[0]['tempo_medio'];
            }
        }

        $tecnicos = array();
        $tecnicos[0]['tecnico_id'] = 126;
        $tecnicos[0]['username'] = 'filipe.augusto';
        $tecnicos[1]['tecnico_id'] = 127;
        $tecnicos[1]['username'] = 'isadora.viegas';
        $tecnicos[2]['tecnico_id'] = 119;
        $tecnicos[2]['username'] = 'leticia.oliveira';
        $tecnicos[3]['tecnico_id'] = 114;
        $tecnicos[3]['username'] = 'layanne.fernandes';
        $tecnicos[4]['tecnico_id'] = 118;
        $tecnicos[4]['username'] = 'bruna.ferreira';
        $tecnicos[5]['tecnico_id'] = 132;
        $tecnicos[5]['username'] = 'miguel.felipe';
        $tecnicos[6]['tecnico_id'] = 131;
        $tecnicos[6]['username'] = 'kayo.heytor';
        $tecnicos[7]['tecnico_id'] = 121;
        $tecnicos[7]['username'] = 'yasmim.rodrigues';

        //dd($list[100]);

        for( $i = 0; $i < count($tecnicos); $i++ ){
            $tecnicos[$i]['mmr'] = 0;
            $tecnicos[$i]['qtde_os'] = 0;
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
                }
            }
        }

        dd($tecnicos);
        //pegar tecnicos que vao participar do calculo
        //pegar tempo medio e nivel do servico
    }
}
