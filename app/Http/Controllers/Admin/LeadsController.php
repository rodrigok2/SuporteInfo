<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SUL\LeadsRepository;
use App\Repositories\SUL\SisSistemasRepository;
use Illuminate\Support\Facades\Log;

class LeadsController extends Controller
{
    public function index(Request $request){
        $leads= null;
        $ativos = 0;
        $passivos = 0;
        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;

        $sisSistemasRepository = new SisSistemasRepository();
        $sistemas = $sisSistemasRepository->PesquisarSistemas();

        if(!$sistemas){
            Log::warning('Falha ao carregar lista de sistemas no SUL');
            return redirect('admin/leads')
                ->with("leads", null)
                ->with("sistemas", null)
                ->with('data_inicial', null)
                ->with('data_final', null)
                ->with("ativos", $ativos)
                ->with("passivos", $passivos)
                ->with("erro","Falha ao carregar lista de sistemas no SUL!");
        }

        if($request->filtro_ativo == 1){
            if($data_inicial == null || $data_final == null){
                return redirect('admin/leads')
                    ->with("leads", null)
                    ->with("sistemas", null)
                    ->with('data_inicial', null)
                    ->with('data_final', null)
                    ->with("ativos", $ativos)
                    ->with("passivos", $passivos)
                    ->with("erro","As datas selecionadas são inválidas!");
            }

            if($request->data_inicial != null && $request->data_final != null){
                $leadsRepository = new LeadsRepository();
                $leads = $leadsRepository->LeadsAgrupadosPorOrigem($request->data_inicial,$request->data_final, $request->sistema_id);

                if(!$leads){
                    Log::warning('Falha ao carregar leads no SUL');
                    return view("admin.leads.index")
                    ->with("leads", null)
                    ->with("sistemas", null)
                    ->with('data_inicial', $data_inicial)
                    ->with('data_final', $data_final)
                    ->with("ativos", $ativos)
                    ->with("passivos", $passivos)
                    ->with("erro","Falha ao carregar leads no SUL!");
                }
                else{
                    for($i=0; $i < count($leads); $i++){
                        if($leads[$i]['origem'] == 'Prospecção Ativa'){
                            $ativos += $leads[$i]['total'];
                        }
                        elseif($leads[$i]['origem'] == 'Prospecção Madeireiras'){
                            $ativos += $leads[$i]['total'];
                        }
                        else{
                            $passivos += $leads[$i]['total'];
                        }
                    }
                }
            }
        }

        return view("admin.leads.index", compact('data_inicial','data_final','leads','sistemas','ativos','passivos'));
    }

    function detalhes(Request $request){
        return view ('admin.leads.detalhes');
    }
}
