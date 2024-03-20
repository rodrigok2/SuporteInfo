<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SUL\UsersRepository;
use App\Repositories\SUL\SisPrioridadesRepository;
use App\Repositories\SUL\SisOsTiposRepository;
use App\Repositories\SUL\SisOsRepository;
use App\Validations\DataValidations;
use App\Repositories\SUL\SisOsAndamentosRepository;
use App\Repositories\SUL\SisSquadsRepository;

class OsController extends Controller
{
    function index (Request $request){
        //Variavel para buscar apenas os tecnicos com cadastro ativo
        $status_users = 'ativos';

        //Pesquisa os usuarios ativos do SUL
        $usersRepository = new UsersRepository();
        $tecnicos = $usersRepository->PesquisarUsuarios($status_users);

        //Se falhar a conexão ou a pesquisa dos usuarios, retorna a view index com erro
        if(!$tecnicos){
            return redirect('admin/os/index')
            ->with('tecnicos', null)
            ->with('prioridades', null)
            ->with('classificacoes', null)
            ->with('squads', null)
            ->with('erro', 'Falha ao pesquisar usuários do SUL!');
        }

        //Ordena o array de usuários por username
        array_multisort(array_column($tecnicos, 'username'), SORT_ASC, $tecnicos);

        //Pesquisa as prioridades de O.S. no SUL
        $sisPrioridadesRepository = new SisPrioridadesRepository();
        $prioridades = $sisPrioridadesRepository->PesquisarPrioridades();

        //Se falhar a conexão ou a pesquisa das prioridades, retorna a view index com erro
        if(!$prioridades){
            return redirect('admin/os/index')
            ->with('tecnicos', $tecnicos)
            ->with('prioridades', null)
            ->with('classificacoes', null)
            ->with('squads', null)
            ->with('erro', 'Falha ao pesquisar lista de prioridades do SUL!');
        }

        //Pesquisa as classificações de O.S. no SUL
        $sisOsTiposRepository = new SisOsTiposRepository();
        $classificacoes = $sisOsTiposRepository->PesquisarTiposOs();

        //Se falhar a conexão ou a pesquisa das classificações, retorna a view index com erro
        if(!$classificacoes){
            return redirect('admin/os/index')
            ->with('tecnicos', $tecnicos)
            ->with('prioridades', $prioridades)
            ->with('classificacoes', null)
            ->with('squads', null)
            ->with('erro', 'Falha ao pesquisar lista de classificações do SUL!');
        }

        //Pesquisa os squads no SUL
        $sisSquadsRepository = new SisSquadsRepository();
        $squads = $sisSquadsRepository->PesquisarSquads();

        //Se falhar a conexão ou a pesquisa dos squads, retorna a view index com erro
        if(!$squads){
            return redirect('admin/os/index')
            ->with('tecnicos', $tecnicos)
            ->with('prioridades', $prioridades)
            ->with('classificacoes', $classificacoes)
            ->with('squads', null)
            ->with('erro', 'Falha ao pesquisar lista de squads do SUL!');
        }

        $os_detalhes = null;

        //Validar se foi feito algum filtro na view index, caso contrario carrega a view em branco
        if($request->filtro_ativo == null){
            return view ('admin.os.index', compact('tecnicos','prioridades','classificacoes', 'squads', 'os_detalhes'));
        }

        //Validação de datas
        $dataValida = true;
        $dataValidation = new DataValidations();
        if($request->data_abertura_inicial != null && !$dataValidation->validarData($request->data_abertura_inicial)){
            return redirect('admin/os/index')->with('erro', 'A data inicial de abertura informada é inválida!');
        }
        if($request->data_abertura_final != null && !$dataValidation->validarData($request->data_abertura_final)){
            return redirect('admin/os/index')->with('erro', 'A data final de abertura informada é inválida!');
        }
        if($request->data_fechamento_inicial != null && !$dataValidation->validarData($request->data_fechamento_inicial)){
            return redirect('admin/os/index')->with('erro', 'A data inicial de fechamento informada é inválida!');
        }
        if($request->data_fechamento_final != null && !$dataValidation->validarData($request->data_fechamento_final)){
            return redirect('admin/os/index')->with('erro', 'A data final de fechamento informada é inválida!');
        }

        //valida numero de O.S.
        if($request->os_id != null){
            if(intval($request->os_id) <= 0){
                return redirect('admin/os/index')->with('erro', 'O numero de O.S. informado é inválido!');
            }
        }

        //valida numero do Cliente
        if($request->cliente_id != null){
            if(intval($request->cliente_id) <= 0){
                return redirect('admin/os/index')->with('erro', 'O numero do cliente informado é inválido!');
            }
        }

        //valida numero do Contrato
        if($request->contrato_id != null){
            if(intval($request->contrato_id) <= 0){
                return redirect('admin/os/index')->with('erro', 'O numero do contrato informado é inválido!');
            }
        }

        $dadosRequest = [
            'cliente_id' => $request->cliente_id,
            'contrato_id' => $request->contrato_id,
            'array_contratos' => null,
            'cnpj' => $request-> cnpj,
            'fantasia' => $request->fantasia,
            'razao_social' => $request->razao_social,
            'data_abertura_inicial' => $request->data_abertura_inicial,
            'data_abertura_final' => $request->data_abertura_final,
            'data_fechamento_inicial' => $request->data_fechamento_inicial,
            'data_fechamento_final' => $request->data_fechamento_final,
            'tecnico_id' => $request->tecnico_id,
            'prioridade_id' => $request->prioridade_id,
            'status_id' => $request->status_id,
            'os_id' => $request->os_id,
            'classificacao_id' => $request->classificacao_id,
            'squad_id' => $request->squad_id,
        ];

        $ordemServicoRepository = new SisOsRepository();
        $os_detalhes = $ordemServicoRepository->PesquisarOs($dadosRequest);
        //Se falhar a conexão ou a pesquisa de O.S., retorna a view index com erro
        if(!$os_detalhes){
            return redirect('admin/os/index')->with('erro', 'Falha ao pesquisar lista de O.S. do SUL!');
        }

        return view ('admin.os.index', compact('tecnicos','prioridades','classificacoes', 'squads', 'os_detalhes'));
    }

    function detalhes ($os_id){
        $ordemServicoRepository = new SisOsRepository();
        $os_detalhes = $ordemServicoRepository->OsPorId($os_id);

        $andamentoRepository = new SisOsAndamentosRepository();
        $andamentos_os = $andamentoRepository->andamentoPorOsId($os_id);

        //dd($os_detalhes);
        return view ('admin.os.detalhes', compact('os_detalhes', 'andamentos_os'));
    }
}
