<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Validations\DataValidations;
use App\Repositories\SUL\SisOsAndamentosRepository;

class ServicoController extends Controller
{
    function index(Request $request)
    {
        $data_inicial = null;
        $data_final = null;
        $lista_de_servicos = null;

        if(!$request->filled('data_inicial') && !$request->filled('data_final')){
            return view ('admin.servicos.index', compact('lista_de_servicos', 'data_inicial', 'data_final'));
        }
        else{
            $dataValidation = new DataValidations();
            $data_inicial = $request->data_inicial;
            $data_final = $request->data_final;

            if ($dataValidation->validarDatas($data_inicial, $data_final)){
                $rankServicos = new SisOsAndamentosRepository();
                $lista_de_servicos = $rankServicos->AndamentosPorDatas($data_inicial, $data_final);

                return view ('admin.servicos.index', compact('lista_de_servicos', 'data_inicial', 'data_final'));
            }
            else{
                return redirect('admin/servicos')->with('erro', 'Data inválida ou não informada!');
            }
        }
    }

    function detalhes (Request $request){
        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;
        $servico_id = $request->servico_id;
        $descricao_grupo = $request->descricao_grupo;
        $descricao_subgrupo = $request->descricao_subgrupo;
        $descricao_servico = $request->descricao_servico;

        $rankServicos = new SisOsAndamentosRepository();
        $lista_de_andamentos = $rankServicos->AndamentoPorServico($data_inicial, $data_final, $servico_id);

        return view ('admin.servicos.detalhes', compact('lista_de_andamentos','descricao_grupo','descricao_subgrupo','descricao_servico'));
    }
}
