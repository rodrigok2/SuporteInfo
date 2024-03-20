<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Mail\NovaOSClienteVip;
use App\Repositories\Local\VipRepository;
use Illuminate\Http\Request;
use App\Repositories\SUL\SisClientesRepository;
use App\Repositories\SUL\SisContratosRepository;
use App\Repositories\SUL\SisOsRepository;
use App\Repositories\SUL\SisSistemasRepository;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class VipController extends Controller
{
    public function index(){
        $vips = null;

        //verificar se a tabela vips possui registros
        $vipRepository = new VipRepository();
        $vips = $vipRepository->PesquisarVips();

        if(!$vips){
            $vips = null;
            return view ('admin.vip.index', compact('vips'));
        }

        $dadosVips = array();

        for($i = 0; $i < count($vips); $i++){
            $sisContratosRepository = new SisContratosRepository();
            $contratoCliente = $sisContratosRepository->ContratoPorId($vips[$i]['contrato_id']);

            if(!$contratoCliente){
                return redirect('admin/vip')->with('erro', 'Falha ao pesquisar dados do contrato do cliente!');
            }

            $sisClientesRepository = new SisClientesRepository();
            $dadosCliente = $sisClientesRepository->ClientePorId($contratoCliente[0]['cliente_id']);

            if(!$dadosCliente){
                return redirect('admin/vip')->with('erro', 'Falha ao pesquisar dados do cliente!');
            }

            $dadosVips[$i]['cliente_id'] = $contratoCliente[0]['cliente_id'];
            $dadosVips[$i]['razao_social'] = $dadosCliente[0]['razao_social'];
            $dadosVips[$i]['fantasia'] = $dadosCliente[0]['fantasia'];
            $dadosVips[$i]['cnpj'] = $dadosCliente[0]['cnpj'];
            $dadosVips[$i]['contrato_id'] = $vips[$i]['contrato_id'];
            $dadosVips[$i]['contrato_status'] = $contratoCliente[0]['contrato_status'];
            $dadosVips[$i]['ultima_os'] = $vips[$i]['os_id'];
            $dadosVips[$i]['vip_id'] = $vips[$i]['vip_id'];
        }

        $vips = $dadosVips;

        return view ('admin.vip.index', compact('vips'));
    }

    public function pesquisar(Request $request){
        //Validar se foi feito algum filtro na view index, caso contrario carrega a view em branco
        if($request->filtro_ativo == null){
            $clientes = null;
            return view ('admin.vip.pesquisar', compact('clientes'));
        }

        //valida numero do Cliente
        if($request->cliente_id != null){
            if(intval($request->cliente_id) <= 0){
                return redirect('admin/vip/pesquisar')->with('erro', 'O numero do cliente informado é inválido!');
            }
            else{
                $sisClientesRepository = new SisClientesRepository();
                $dadosCliente = $sisClientesRepository->ClientePorId($request->cliente_id);

                if(!$dadosCliente){
                    return redirect('admin/vip/pesquisar')->with('erro', 'Falha ao pesquisar dados do cliente!');
                }

                $sisContratosRepository = new SisContratosRepository();
                $contratosCliente = $sisContratosRepository->ContratoAtivoPorClienteId($request->cliente_id);

                $sisSistemasRepository = new SisSistemasRepository();

                $clientes = array();
                for($i = 0; $i < count($contratosCliente); $i++){
                    $sistema = $sisSistemasRepository->SistemaPorId($contratosCliente[$i]['sistema_id']);

                    if(!$sistema){
                        return redirect('admin/vip/pesquisar')->with('erro', 'Falha ao pesquisar dados do sistema do cliente!');
                    }
                    else{
                        $clientes[$i]['cliente_id'] = $request->cliente_id;
                        $clientes[$i]['razao_social'] = $dadosCliente[0]['razao_social'];
                        $clientes[$i]['fantasia'] = $dadosCliente[0]['fantasia'];
                        $clientes[$i]['cnpj'] = $dadosCliente[0]['cnpj'];
                        $clientes[$i]['contrato_id'] = $contratosCliente[$i]['contrato_id'];
                        $clientes[$i]['contrato_status'] = $contratosCliente[$i]['contrato_status'];
                        $clientes[$i]['sistema_id'] = $contratosCliente[$i]['sistema_id'];
                        $clientes[$i]['sistema'] = $sistema[0]['sistema'];
                    }
                }

                //Ordena o array por contrato_id
                array_multisort(array_column($clientes, 'contrato_id'), SORT_DESC, $clientes);

                return view ('admin.vip.pesquisar', compact('clientes'));
            }
        }

        //valida numero do Contrato
        if($request->contrato_id != null){
            if(intval($request->contrato_id) <= 0){
                return redirect('admin/vip/pesquisar')->with('erro', 'O numero do contrato informado é inválido!');
            }
            else{
                $sisContratosRepository = new SisContratosRepository();
                $contratoCliente = $sisContratosRepository->ContratoAtivoPorId($request->contrato_id);

                $sisClientesRepository = new SisClientesRepository();
                $dadosCliente = $sisClientesRepository->ClientePorId($contratoCliente[0]['cliente_id']);

                if(!$dadosCliente){
                    return redirect('admin/vip/pesquisar')->with('erro', 'Falha ao pesquisar dados do cliente!');
                }

                $sisSistemasRepository = new SisSistemasRepository();
                $sistema = $sisSistemasRepository->SistemaPorId($contratoCliente[0]['sistema_id']);

                if(!$sistema){
                    return redirect('admin/vip/pesquisar')->with('erro', 'Falha ao pesquisar dados do sistema do cliente!');
                }

                $clientes = array();
                $clientes[0]['cliente_id'] = $contratoCliente[0]['cliente_id'];
                $clientes[0]['razao_social'] = $dadosCliente[0]['razao_social'];
                $clientes[0]['fantasia'] = $dadosCliente[0]['fantasia'];
                $clientes[0]['cnpj'] = $dadosCliente[0]['cnpj'];
                $clientes[0]['contrato_id'] = $request->contrato_id;
                $clientes[0]['contrato_status'] = $contratoCliente[0]['contrato_status'];
                $clientes[0]['sistema_id'] = $contratoCliente[0]['sistema_id'];
                $clientes[0]['sistema'] = $sistema[0]['sistema'];

                return view ('admin.vip.pesquisar', compact('clientes'));
            }
        }

        //valida se foi passado numero de CNPJ como parametro
        if($request->cnpj != null){
            $sisClientesRepository = new SisClientesRepository();
            $dadosCliente = $sisClientesRepository->ClientePorCPNJ($request->cnpj);

            if(!$dadosCliente){
                return redirect('admin/vip/pesquisar')->with('erro', 'O CNPJ informado é inválido!');
            }

            $sisContratosRepository = new SisContratosRepository();
            $contratosCliente = $sisContratosRepository->ContratoAtivoPorClienteId($dadosCliente[0]['cliente_id']);

            $sisSistemasRepository = new SisSistemasRepository();

            $clientes = array();
            for($i = 0; $i < count($contratosCliente); $i++){
                $sistema = $sisSistemasRepository->SistemaPorId($contratosCliente[$i]['sistema_id']);

                if(!$sistema){
                    return redirect('admin/vip/pesquisar')->with('erro', 'Falha ao pesquisar dados do sistema do cliente!');
                }
                else{
                    $clientes[$i]['cliente_id'] = $dadosCliente[0]['cliente_id'];
                    $clientes[$i]['razao_social'] = $dadosCliente[0]['razao_social'];
                    $clientes[$i]['fantasia'] = $dadosCliente[0]['fantasia'];
                    $clientes[$i]['cnpj'] = $dadosCliente[0]['cnpj'];
                    $clientes[$i]['contrato_id'] = $contratosCliente[$i]['contrato_id'];
                    $clientes[$i]['contrato_status'] = $contratosCliente[$i]['contrato_status'];
                    $clientes[$i]['sistema_id'] = $contratosCliente[$i]['sistema_id'];
                    $clientes[$i]['sistema'] = $sistema[0]['sistema'];
                }
            }

            //Ordena o array por contrato_id
            array_multisort(array_column($clientes, 'contrato_id'), SORT_DESC, $clientes);

            return view ('admin.vip.pesquisar', compact('clientes'));
        }

        //valida se foi passado nome Fantasia como parametro
        if($request->fantasia != null){
            $sisClientesRepository = new SisClientesRepository();
            $dadosClientes = $sisClientesRepository->ClientePorFantasia($request->fantasia);

            if(!$dadosClientes){
                return redirect('admin/vip/pesquisar')->with('erro', 'Erro ao pesquisar o nome fantasia do cliente!');
            }

            $clientes = array();
            $count = 0;
            for($j = 0; $j < count($dadosClientes); $j++){
                $sisContratosRepository = new SisContratosRepository();
                $contratosCliente = $sisContratosRepository->ContratoAtivoPorClienteId($dadosClientes[$j]['cliente_id']);

                $sisSistemasRepository = new SisSistemasRepository();

                for($i = 0; $i < count($contratosCliente); $i++){
                    $sistema = $sisSistemasRepository->SistemaPorId($contratosCliente[$i]['sistema_id']);

                    if(!$sistema){
                        return redirect('admin/vip/pesquisar')->with('erro', 'Falha ao pesquisar dados do sistema do cliente!');
                    }
                    else{
                        $clientes[$count]['cliente_id'] = $dadosClientes[$j]['cliente_id'];
                        $clientes[$count]['razao_social'] = $dadosClientes[$j]['razao_social'];
                        $clientes[$count]['fantasia'] = $dadosClientes[$j]['fantasia'];
                        $clientes[$count]['cnpj'] = $dadosClientes[$j]['cnpj'];
                        $clientes[$count]['contrato_id'] = $contratosCliente[$i]['contrato_id'];
                        $clientes[$count]['contrato_status'] = $contratosCliente[$i]['contrato_status'];
                        $clientes[$count]['sistema_id'] = $contratosCliente[$i]['sistema_id'];
                        $clientes[$count]['sistema'] = $sistema[0]['sistema'];
                        $count++;
                    }
                }
            }

            //Ordena o array por contrato_id
            array_multisort(array_column($clientes, 'razao_social'), SORT_ASC, array_column($clientes, 'contrato_id'), SORT_DESC, $clientes);

            return view ('admin.vip.pesquisar', compact('clientes'));
        }

        //valida se foi passado a razao social como parametro
        if($request->razao_social != null){
            $sisClientesRepository = new SisClientesRepository();
            $dadosClientes = $sisClientesRepository->ClientePorRazaoSocial($request->razao_social);

            if(!$dadosClientes){
                return redirect('admin/vip/pesquisar')->with('erro', 'Erro ao pesquisar a razão social do cliente!');
            }

            $clientes = array();
            $count = 0;
            for($j = 0; $j < count($dadosClientes); $j++){
                $sisContratosRepository = new SisContratosRepository();
                $contratosCliente = $sisContratosRepository->ContratoAtivoPorClienteId($dadosClientes[$j]['cliente_id']);

                $sisSistemasRepository = new SisSistemasRepository();

                for($i = 0; $i < count($contratosCliente); $i++){
                    $sistema = $sisSistemasRepository->SistemaPorId($contratosCliente[$i]['sistema_id']);

                    if(!$sistema){
                        return redirect('admin/vip/pesquisar')->with('erro', 'Falha ao pesquisar dados do sistema do cliente!');
                    }
                    else{
                        $clientes[$count]['cliente_id'] = $dadosClientes[$j]['cliente_id'];
                        $clientes[$count]['razao_social'] = $dadosClientes[$j]['razao_social'];
                        $clientes[$count]['fantasia'] = $dadosClientes[$j]['fantasia'];
                        $clientes[$count]['cnpj'] = $dadosClientes[$j]['cnpj'];
                        $clientes[$count]['contrato_id'] = $contratosCliente[$i]['contrato_id'];
                        $clientes[$count]['contrato_status'] = $contratosCliente[$i]['contrato_status'];
                        $clientes[$count]['sistema_id'] = $contratosCliente[$i]['sistema_id'];
                        $clientes[$count]['sistema'] = $sistema[0]['sistema'];
                        $count++;
                    }
                }
            }

            //Ordena o array por contrato_id
            array_multisort(array_column($clientes, 'razao_social'), SORT_ASC, array_column($clientes, 'contrato_id'), SORT_DESC, $clientes);

            return view ('admin.vip.pesquisar', compact('clientes'));
        }
    }

    public function cadastrar(Request $request){
        if(Gate::denies('administrador')){
            return redirect('admin/vip')->with('erro', 'Acesso negado! Você não tem permissão para executar essa ação!');
        }

        $sisOsRepository = new SisOsRepository();
        $os_id = $sisOsRepository->UltimaOsPorContratoId($request->contrato);

        if(!$os_id){
            return redirect('admin/vip/pesquisar')->with('erro', 'Erro ao pesquisar ultima O.S. do cliente!');
        }

        $dadosRequest = [
            'contrato_id' => $request->contrato,
            'os_id' => $os_id[0]['os_id'],
        ];

        $vipRepository = new VipRepository();
        $vip = $vipRepository->CadastrarVip($dadosRequest);

        if($vip){
            return redirect('admin/vip')->with('sucesso', 'Cliente Vip cadastrado com sucesso!');
        }
        else{
            return redirect('admin/vip')->with('erro', 'Falha ao cadastrar cliente Vip!');
        }
    }

    public function excluir($vip_id){
        if(Gate::denies('administrador')){
            return redirect('admin/vip')->with('erro', 'Acesso negado! Você não tem permissão para executar essa ação!');
        }

        $vipRepository = new VipRepository();
        $vip = $vipRepository->ExcluirVip($vip_id);

        if($vip){
            return redirect('admin/vip')->with('info', 'Cliente Vip removido com sucesso!');
        }
        else{
            return redirect('admin/vip')->with('erro', 'Falha ao excluir cliente Vip!');
        }

    }
}
