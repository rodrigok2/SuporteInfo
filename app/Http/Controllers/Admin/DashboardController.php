<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\SUL\SisOsAndamentosRepository;
use App\Repositories\SUL\SisOsRepository;
use App\Repositories\Local\VipRepository;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    function index(){
        $user = Auth::user();

        //dd($teste);
        $today = date("Y-m-d");
        $data_inicial = $today." 00:00:00";
        $data_final = $today." 23:59:59";

        //Card de andamentos
        $sisOsAndamentosRepository = new SisOsAndamentosRepository();
        $total_andamento = $sisOsAndamentosRepository->TempoTotalPorTecnico($data_inicial, $data_final, $user->id);

        //Card de OS abertas
        $sisOsRepository = new SisOsRepository();
        $total_os_abertas = $sisOsRepository->TotalOsAbertaPorTecnico($user->id);

        //Card de OS fechadas
        $total_os_fechadas = $sisOsRepository->TotalOsFechadasPorTecnico($data_inicial, $data_final, $user->id);

        //Tabela de clientes Vips
        $vipRepository = new VipRepository();
        $vips = $vipRepository->PesquisarVips();
        //dd($vips);
        $os_vips = null;
        if($vips){
            $classificacao_id = array(
                '1','2','3','4','5','11',
                '12','13','14','15','17',
                '19','20','22','23','25',
                '26','28','29','31'
            );
            $contratos = array();
            for($i = 0; $i < count($vips); $i++){
                array_push($contratos, $vips[$i]['contrato_id']);
            }
            $dadosRequest = [
                'cliente_id' => null,
                'contrato_id' => null,
                'array_contratos' => $contratos,
                'cnpj' => null,
                'fantasia' => null,
                'razao_social' => null,
                'data_abertura_inicial' => null,
                'data_abertura_final' => null,
                'data_fechamento_inicial' => null,
                'data_fechamento_final' => null,
                'tecnico_id' => null,
                'prioridade_id' => null,
                'status_id' => '1',
                'os_id' => null,
                'classificacao_id' => $classificacao_id,
                'squad_id' => null,
            ];

            $os_vips = $sisOsRepository->PesquisarOs($dadosRequest);
        }

        $classificacao_id = array('22','3','31','1');
        $primeiroDiaMesAtual = date('Y-m-01');
        $data_inicial = date('Y-m-d', strtotime('-11 month', strtotime($primeiroDiaMesAtual)));
        $data_inicial = $data_inicial." 00:00:00";
        $dadosGrafico = $sisOsRepository->TotalOsFechadaPorMesSuporte($data_inicial, $data_final, $classificacao_id);

        if(!$total_andamento || !$total_os_abertas || !$total_os_fechadas || !$vips || !$dadosGrafico || !$os_vips){
            if(!$total_andamento){
                Log::warning('O dashboard falhou ao carregar o total de andamentos do usuario');
                $total_andamento = null;
            }
            if(!$total_os_abertas){
                Log::warning('O dashboard falhou ao carregar o total de O.S. abertas do usuario');
                $total_os_abertas = null;
            }
            if(!$total_os_fechadas){
                Log::warning('O dashboard falhou ao carregar o total de O.S. fechadas do usuario');
                $total_os_fechadas = null;
            }
            if(!$vips){
                Log::warning('O dashboard falhou ao carregar os clientes vips');
                $vips = null;
            }
            if(!$dadosGrafico){
                Log::warning('O dashboard falhou ao carregar os dados do grafico de 24 horas');
                $dadosGrafico = null;
            }
            if(!$os_vips){
                Log::warning('O dashboard falhou ao carregar as O.S. dos clientes vips');
                $os_vips = null;
            }
            return view('admin.dashboard.index')
            ->with('user', $user)
            ->with('total_andamento', $total_andamento)
            ->with('total_os_abertas', $total_os_abertas)
            ->with('total_os_fechadas', $total_os_fechadas)
            ->with('os_vips', $os_vips)
            ->with('dadosGrafico', $dadosGrafico);
        }
        //dd($total_os_abertas);
        return view ('admin.dashboard.index', compact('user', 'total_andamento', 'total_os_abertas', 'total_os_fechadas', 'os_vips', 'dadosGrafico'));
    }

    function painel(){
        $user = Auth::user();

        $today = date("Y-m-d");
        $data_inicial = $today." 00:00:00";
        $data_final = $today." 23:59:59";

        $sisOsAndamentosRepository = new SisOsAndamentosRepository();
        $total_andamento = $sisOsAndamentosRepository->TempoTotalPorTecnico($data_inicial, $data_final, $user->id);

        return view ('admin.dashboard.painel', compact('user', 'total_andamento'));


        //return view('admin.dashboard.painel');
    }
}
