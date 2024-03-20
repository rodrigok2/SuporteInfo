<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SUL\UsersRepository;
use App\Validations\DataValidations;
use App\Repositories\SUL\SisOsAndamentosRepository;

class AndamentoController extends Controller
{
    function index(Request $request){
        $andamentos = null;

        //Variavel para buscar apenas os tecnicos com cadastro ativo
        $status_users = 'ativos';

        //Pesquisa os usuarios ativos do SUL
        $usersRepository = new UsersRepository();
        $tecnicos = $usersRepository->PesquisarUsuarios($status_users);

        //Se falhar a conexão ou a pesquisa dos usuarios, retorna a view index com erro
        if(!$tecnicos){
            return redirect('admin/andamentos')
            ->with('tecnicos', null)
            ->with('andamentos', $andamentos)
            ->with('erro', 'Falha ao carregar os usuários do SUL!');
        }
        //Ordena o array de usuários por username
        array_multisort(array_column($tecnicos, 'username'), SORT_ASC, $tecnicos);

        if($request->filtro_ativo == null){
            return view ('admin.andamentos.index', compact ('andamentos','tecnicos'));
        }

        //Validação de datas
        $dataValida = true;
        $dataValidation = new DataValidations();
        if(!$dataValidation->validarData($request->data_inicial)){
            return redirect('admin/andamentos')
            ->with('tecnicos', $tecnicos)
            ->with('andamentos', $andamentos)
            ->with('erro', 'A data inicial informada é inválida!');
        }
        if(!$dataValidation->validarData($request->data_final)){
            return redirect('admin/andamentos')
            ->with('tecnicos', $tecnicos)
            ->with('andamentos', $andamentos)
            ->with('erro', 'A data final informada é inválida!');
        }

        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;
        $tecnico_id = $request->tecnico_id;

        if($tecnico_id == null){
            return redirect('admin/andamentos')
            ->with('tecnicos', $tecnicos)
            ->with('andamentos', $andamentos)
            ->with('erro', 'Selecione um Técnico!');
        }

        $sisOsAndamentosRepository = new SisOsAndamentosRepository();
        $andamentos = $sisOsAndamentosRepository->AndamentoPorTecnico($data_inicial, $data_final, $tecnico_id);

        if(!$andamentos){
            return redirect('admin/andamentos')
            ->with('tecnicos', $tecnicos)
            ->with('andamentos', null)
            ->with('erro', 'Erro ao buscar andamentos no SUL');
        }

        //Ordena o array de andamentos por id
        array_multisort(array_column($andamentos, 'andamento_id'), SORT_ASC, $andamentos);

        //dd($andamentos);
        return view ('admin.andamentos.index', compact ('andamentos','tecnicos'));
    }
}
