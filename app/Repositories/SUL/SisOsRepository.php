<?php

namespace App\Repositories\SUL;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\SUL\UsersRepository;
use App\Repositories\SUL\SisOsTiposRepository;
use App\Repositories\SUL\SisContratosRepository;
use App\Repositories\SUL\SisClientesRepository;
use App\Repositories\SUL\SisPrioridadesRepository;
use App\Repositories\SUL\SisTreinamentoUsuarioRepository;

class SisOsRepository{
    function PesquisarOs($dadosRequest){
        try{
            if($dadosRequest['array_contratos'] != null){
                $contratos = $dadosRequest['array_contratos'];
            }
            else{
                $contratos = null;
            }

            /*
            *Bloco para carregar um array com os contratos dos clientes
            *Caso algum filtro de cliente/contrato tenha sido preenchido na view
            */

            //Caso algum filtro de cliente tenha sido preenchido, primeiro é preciso selecionar os contratos desse cliente
            //cria uma instancia da classe SisContratosRepository
            $sisContratosRepository = new SisContratosRepository();
            //Se foi passado o Id do cliente, pega os contratos referente ao cliente
            if($dadosRequest['cliente_id'] != null){
                $contrato_filtro = $sisContratosRepository->ContratoPorClienteId($dadosRequest['cliente_id']);
                //Lança uma exceção caso falhe a pesquisa por contratos no sul
                if(!$contrato_filtro){
                    throw new Exception("Falha ao pesquisar os contratos no SUL");
                }
                //Cliente pode ter mais de 1 contrato, por isso cria string com todos os contratos para usar no Where
                $contratos = array();
                for($i = 0; $i < count($contrato_filtro); $i++){
                    array_push($contratos, $contrato_filtro[$i]['contrato_id']);
                }
            }
            else{
                $cliente_filtro = null;

                //Cria uma instancia da classe SisClientesRepository
                $sisClientesRepository = new SisClientesRepository();
                if($dadosRequest['cnpj'] != null){
                    $cliente_filtro = $sisClientesRepository->ClientePorCPNJ($dadosRequest['cnpj']);
                    //Lança uma exceção caso falhe a pesquisa por cnpj do cliente falhe
                    if(!$cliente_filtro){
                        throw new Exception("Falha ao pesquisar o cliente por CNPJ no SUL");
                    }
                }
                elseif($dadosRequest['razao_social'] != null){
                    $cliente_filtro = $sisClientesRepository->ClientePorRazaoSocial($dadosRequest['razao_social']);
                    //Lança uma exceção caso falhe a pesquisa por razao social do cliente falhe
                    if(!$cliente_filtro){
                        throw new Exception("Falha ao pesquisar o cliente pela razao social no SUL");
                    }
                }
                elseif($dadosRequest['fantasia'] != null){
                    $cliente_filtro = $sisClientesRepository->ClientePorFantasia($dadosRequest['fantasia']);
                    //Lança uma exceção caso falhe a pesquisa por nome fantasia do cliente falhe
                    if(!$cliente_filtro){
                        throw new Exception("Falha ao pesquisar o cliente pelo nome fantasia no SUL");
                    }
                }

                if($cliente_filtro != null){
                    //O filtro de clientes pode ter retornado mais de um cliente
                    //For de clientes e iniciação da variavel contratos
                    $contratos = array();
                    for($i = 0; $i < count($cliente_filtro); $i++){
                        //consulta os contratos do cliente atual
                        $contrato_filtro = $sisContratosRepository->ContratoPorClienteId($cliente_filtro[$i]['cliente_id']);

                        //Lança uma exceção caso falhe a pesquisa por contratos no sul
                        if(!$contrato_filtro){
                            throw new Exception("Falha ao pesquisar os contratos no SUL");
                        }
                        for($j = 0; $j < count($contrato_filtro); $j++){
                            array_push($contratos, $contrato_filtro[$j]['contrato_id']);
                        }
                    }
                }
            }

            /*
            *Bloco para consultar as O.S. no SUL
            */
            //Pesquisa detalhes da OS
            $list = DB::connection('mysql_sul')->table("sis_os")
                ->select(
                    "sis_os.id as os_id",
                    "sis_os.contrato as contrato_id",
                    "sis_os.status as os_status",
                    "sis_os.info as descricao",
                    "sis_os.data_inicio as data_inicial",
                    "sis_os_tipos.nome as classificacao_os",
                    "users.username as tecnico",
                )
                ->leftJoin('users', 'users.id', '=', 'sis_os.responsavel')
                ->join('sis_os_tipos', 'sis_os_tipos.id', '=', 'sis_os.classificacao');

            //verifica se foi passado numero do contrato no filtro e inclui na consulta SQL

            if($contratos != null){
                $list->whereIn('sis_os.contrato', $contratos);
                //$list->where("sis_os.contrato", "in", $contratos);
            }
            elseif($dadosRequest['contrato_id'] != null){
                //$list->where("sis_os.contrato", "in", $dadosRequest['contrato_id']);
                $list->where("sis_os.contrato", "=", $dadosRequest['contrato_id']);
            }

            //verifica se foi passado numero de O.S. no filtro e inclui na consulta SQL
            if($dadosRequest['os_id'] != null){
                $list->where("sis_os.id", "=", $dadosRequest['os_id']);
            }

            //verifica se foi passado data de abertura no filtro e inclui na consulta SQL
            if($dadosRequest['data_abertura_inicial'] != null){
                $list->where("sis_os.data_inicio", ">=", $dadosRequest['data_abertura_inicial']." 00:00:00");
            }

            //verifica se foi passado data de abertura no filtro e inclui na consulta SQL
            if($dadosRequest['data_abertura_final'] != null){
                $list->where("sis_os.data_inicio", "<=", $dadosRequest['data_abertura_final']." 23:59:59");
            }

            //verifica se foi passado data de fechamento no filtro e inclui na consulta SQL
            if($dadosRequest['data_fechamento_inicial'] != null){
                $list->where("sis_os.data_fim", ">=", $dadosRequest['data_fechamento_inicial']." 00:00:00");
            }

            //verifica se foi passado data de fechamento no filtro e inclui na consulta SQL
            if($dadosRequest['data_fechamento_final'] != null){
                $list->where("sis_os.data_fim", "<=", $dadosRequest['data_fechamento_final']." 23:59:59");
            }

            //verifica se foi passado a prioridade da O.S. no filtro e inclui na consulta SQL
            if($dadosRequest['prioridade_id'] != null){
                $list->where("sis_os.prioridade_tarefa_redmine", "=", $dadosRequest['prioridade_id']);
            }

            //verifica se foi passado o status da O.S. no filtro e inclui na consulta SQL
            if($dadosRequest['status_id'] != null){
                $list->where("sis_os.status", "=", $dadosRequest['status_id']);
            }

            //verifica se foi passado o Tecnico da O.S. no filtro e inclui na consulta SQL
            if($dadosRequest['tecnico_id'] != null){
                $list->where("sis_os.responsavel", "=", $dadosRequest['tecnico_id']);
            }

            //verifica se foi passado os tipos de O.S. que devem ser fitlados e inclui na consulta SQL
            if($dadosRequest['classificacao_id'] != null){
                $list->whereIn('sis_os.classificacao', $dadosRequest['classificacao_id']);
            }

            //verifica se foi passado os squad que devem ser fitlados e inclui na consulta SQL
            if($dadosRequest['squad_id'] != null){
                $list->where("sis_os.id_squad", "=", $dadosRequest['squad_id']);
            }

            $list->orderBy("os_id", "asc");

            //Recebe os dados na variável $list
            $list = $list->paginate(10);

            //$list = json_decode(json_encode($list), true);

            return $list;
        }catch(Exception $e){
            Log::critical('SisOsRepository->PesquisarOs', [$e]);
            return false;
        }
    }

    function OsPorId($os_id){
        try{
            //Pesquisa detalhes da OS
            $list = DB::connection('mysql_sul')->table("sis_os")
                ->select(
                    "sis_os.id as os_id",
                    "sis_os.contrato as contrato_id",
                    "sis_os.status as os_status",
                    "sis_os.info as descricao",
                    "sis_os.data_inicio as data_inicial",
                    "sis_os.data_fim as data_final",
                    "sis_os.classificacao as classificacao_os_id",
                    "sis_os.responsavel as tecnico_id",
                    "sis_os.usuario as tecnico_abertura_id",
                    "sis_os.prioridade_tarefa_redmine as prioridade_id",
                    "sis_os.solicitante as solicitante_id",
                    "sis_os.tempo_total as tempo_total",
                    )
                ->where("sis_os.id", "=", $os_id)
                ->get();

            //Converte o resultado da consulta no banco em Array
            $list = json_decode(json_encode($list), true);

            //Cria instancia da Classe UsersRepository para consultar dados dos técnicos
            $usersRepositoy = new UsersRepository();
            //Pesquisa os dados do técnico

            if($list[0]['tecnico_id'] == 0){
                $tecnico = $usersRepositoy->UsuarioPorId($list[0]['tecnico_abertura_id']);
                $list[0]['username'] = $tecnico[0]['username'];
            }
            else{
                $tecnico = $usersRepositoy->UsuarioPorId($list[0]['tecnico_id']);
                $list[0]['username'] = $tecnico[0]['username'];
            }

            //Cria instancia da Classe TipoOrdemServicoRepository para consultar os tipos da O.S.
            $tipoOrdemServicoRepository = new SisOsTiposRepository();
            //Pesquisa a classificacao da OS
            $classificacao = $tipoOrdemServicoRepository->TipoOsPorId($list[0]['classificacao_os_id']);
            $list[0]['classificacao_os'] = $classificacao[0]['classificacao_os'];


            //cria uma instancia da classe SisContratosRepository
            $sisContratosRepository = new SisContratosRepository();
            //Pesquisa os dados do contrato do cliente
            $contrato = $sisContratosRepository->ContratoPorId($list[0]['contrato_id']);

            $list[0]['cliente_id'] = $contrato[0]['cliente_id'];
            $list[0]['contrato_status'] = $contrato[0]['contrato_status'];


            //Cria uma instancia da classe SisClientesRepository
            $sisClientesRepository = new SisClientesRepository();
            //Pesquisa os dados do cliente
            $cliente = $sisClientesRepository->ClientePorId($list[0]['cliente_id']);
            $list[0]['razao_social'] = $cliente[0]['razao_social'];
            $list[0]['fantasia'] = $cliente[0]['fantasia'];
            $list[0]['cnpj'] = $cliente[0]['cnpj'];


            //Cria uma instancia da classe SisPrioridadesRepository
            $sisPrioridadesRepository = new SisPrioridadesRepository();
            //Pesquisa a classificação de prioridade no SUL

            if($list[0]['prioridade_id'] == null){
                $list[0]['prioridade_os'] = 'Normal';
            }
            else{
                $prioridade = $sisPrioridadesRepository->PrioridadePorId($list[0]['prioridade_id']);
                $list[0]['prioridade_os'] = $prioridade[0]['prioridade_os'];
            }

            //Cria instancia da classe SisTreinamentoUsuarioRepository
            $sisTreinamentoUsuarioRepository = new SisTreinamentoUsuarioRepository();
            //Pesquisar solicitante da OS
            $solicitante = $sisTreinamentoUsuarioRepository->SolicitantePorId($list[0]['solicitante_id']);
            if(!$solicitante){
                $list[0]['solicitante_nome'] = "";
                $list[0]['solicitante_cpf'] = "";
                $list[0]['solicitante_email'] = "";
                $list[0]['solicitante_telefone'] = "";
            }
            else{
                $list[0]['solicitante_nome'] = $solicitante[0]['solicitante_nome'];
                $list[0]['solicitante_cpf'] = $solicitante[0]['solicitante_cpf'];
                $list[0]['solicitante_email'] = $solicitante[0]['solicitante_email'];
                $list[0]['solicitante_telefone'] = $solicitante[0]['solicitante_telefone'];
            }

            return $list;

        }catch(Exception $e){
            Log::critical('SisOsRepository->OsPorId', [$e]);
            return false;
        }
    }

    function UltimaOsPorContratoId($contrato){
        try{
            $list = DB::connection('mysql_sul')->table("sis_os")
                ->select(
                    DB::raw('max(sis_os.id) as os_id')
                )
                ->where("sis_os.contrato", "=", $contrato)
                ->get();

            $list = json_decode(json_encode($list), true);

            return $list;
        }catch(Exception $e){
            Log::critical('SisOsRepository->UltimaOsPorContratoId', [$e]);
            return false;
        }
    }

    function NovasOSporContratoId($ultimaOs, $contrato_id){
        try{
            $list = DB::connection('mysql_sul')->table("sis_os")
                ->select(
                    'sis_os.id as os_id'
                )
                ->where("sis_os.contrato", "=", $contrato_id)
                ->where("sis_os.id", ">", $ultimaOs)
                ->where("sis_os.status", "=", 1)
                ->get();

            $list = json_decode(json_encode($list), true);

            return $list;
        }catch(Exception $e){
            Log::critical('SisOsRepository->NovasOSporContratoId', [$e]);
            return false;
        }
    }

    function TotalOsAbertaPorTecnico($tecnico_id){
        try{
            //Busca no SUl os andamentos vinculados ao servico e o periodo selecionado
            $list = DB::connection('mysql_sul')->table("sis_os")
                ->select(
                    DB::raw('count(sis_os.id) as total')
                    )
                ->where("sis_os.status", "=", 1)
                ->where("sis_os.responsavel", "=", $tecnico_id)
                ->get();
            return $list;

        }catch(Exception $e){
            Log::critical('SisOsRepository->TotalOsAbertaPorTecnico', [$e]);
            return false;
        }
    }

    function TotalOsFechadasPorTecnico($data_inicial, $data_final, $tecnico_id){
        try{
            //Busca no SUl os andamentos vinculados ao servico e o periodo selecionado
            $list = DB::connection('mysql_sul')->table("sis_os")
                ->select(
                    DB::raw('count(sis_os.id) as total')
                    )
                ->where("sis_os.status", "=", 0)
                ->where("sis_os.data_fim", ">=", $data_inicial)
                ->where("sis_os.data_fim", "<=", $data_final)
                ->where("sis_os.responsavel", "=", $tecnico_id)
                ->get();

            return $list;

        }catch(Exception $e){
            Log::critical('SisOsRepository->TotalOsAbertaPorTecnico', [$e]);
            return false;
        }
    }

    function TotalOsFechadaPorMesSuporte($data_inicial, $data_final, $classificacoes){
        try{
            $list = DB::connection('mysql_sul')->table("sis_os")
                ->select(
                    DB::raw("monthname(sis_os.data_fim) month"),
                    DB::raw("COUNT(sis_os.id) AS total"),
                )
                ->join('sis_contratos', 'sis_contratos.id', '=', 'sis_os.contrato')
                ->join('sis_clientes', 'sis_clientes.id', '=', 'sis_contratos.cliente')
                ->where("sis_os.data_fim", ">=", $data_inicial)
                ->where("sis_os.data_fim", "<=", $data_final)
                ->whereIn('sis_os.classificacao', $classificacoes)
                ->where("sis_os.status", "=", 0)
                ->where("sis_os.id_squad", "=", 11)
                ->where("sis_clientes.id", "<>", 2)
                ->groupBy('month')
                ->orderBy("sis_os.data_fim", "asc")
                ->get();

            $list = json_decode(json_encode($list), true);

            $list2 = DB::connection('mysql_sul')->table("sis_os")
                ->select(
                    DB::raw("monthname(sis_os.data_fim) month"),
                    DB::raw("COUNT(sis_os.id) AS total"),
                )
                ->join('sis_contratos', 'sis_contratos.id', '=', 'sis_os.contrato')
                ->join('sis_clientes', 'sis_clientes.id', '=', 'sis_contratos.cliente')
                ->where("sis_os.data_fim", ">=", $data_inicial)
                ->where("sis_os.data_fim", "<=", $data_final)
                ->whereIn('sis_os.classificacao', $classificacoes)
                ->where("sis_os.status", "=", 0)
                ->where("sis_os.id_squad", "=", 11)
                ->where("sis_clientes.id", "<>", 2)
                ->whereRaw('(TIMESTAMPDIFF(HOUR, sis_os.data_inicio, sis_os.data_fim)) <= 24')
                ->groupBy('month')
                ->orderBy("sis_os.data_fim", "asc")
                ->get();

            $list2 = json_decode(json_encode($list2), true);

            for($i = 0; $i < count($list); $i++){
                $list[$i]["total_24horas"] = $list2[$i]['total'];
                $list[$i]['percentual'] = round(($list2[$i]['total'] / $list[$i]['total']) * 100);
            }

            return $list;

        }catch(Exception $e){
            Log::critical('SisOsRepository->TotalOsFechadaPorMesSuporte', [$e]);
            return false;
        }
    }

    function OsFechadasPorPeriodo($data_inicial, $data_final){
        try{
            //Busca no SUl os andamentos vinculados ao servico e o periodo selecionado
            $list = DB::connection('mysql_sul')->table("sis_os")
                ->select(
                    'sis_os.id as os_id',
                    'sis_os.contrato as contrato_id',
                    'sis_os.status as status',
                    'sis_os.info as descricao',
                    'sis_os.data_inicio as data_abertura',
                    'sis_os.data_fim as data_fechada',
                    'sis_os.classificacao as classificacao_id',
                    'sis_os.responsavel as tecnico_id',
                    'sis_os.prioridade_tarefa_redmine as prioridade_id',
                    'sis_os.tempo_total as tempo',
                    DB::raw("(select servico from sis_os_andamento where id = (
                                select max(sis_os_andamento.id) from sis_os_andamento
                                where os = sis_os.id and servico is not null)) as servico_id")
                    )
                ->where("sis_os.status", "=", 0)
                ->where("sis_os.data_fim", ">=", $data_inicial)
                ->where("sis_os.data_fim", "<=", $data_final)
                ->where("sis_os.id_squad", "=", 11)
                ->where("sis_os.contrato", "<>", 2682)
                ->get();

            $list = json_decode(json_encode($list), true);

            return $list;

        }catch(Exception $e){
            Log::critical('SisOsRepository->OsFechadasPorPeriodo', [$e]);
            return false;
        }
    }

    function rankServicosMaisUtilizados($data_inicial, $data_final, $nivel3){
        try{
            //Busca no SUl os andamentos vinculados ao servico e o periodo selecionado
            $list = DB::connection('mysql_sul')->table("sis_os")
                ->select(
                    "sis_os.ultimo_servico as servico_id",
                    "servicos.descricao as servico_descricao",
                    DB::raw('count(sis_os.ultimo_servico) as total')
                    )
                ->join('servicos', 'sis_os.ultimo_servico', '=', 'servicos.id')
                ->where("sis_os.status", "=", 0)
                ->where("sis_os.data_fim", ">=", $data_inicial)
                ->where("sis_os.data_fim", "<=", $data_final);

            if($nivel3){
                $list->where("servicos.nivel", "=", 3);
            }

            $list->groupBy("sis_os.ultimo_servico");
            $list->orderBy("total", "desc");
            $list = $list->get();

            $list = json_decode(json_encode($list), true);
            dd($list);
            return $list;

        }catch(Exception $e){
            Log::critical('SisOsRepository->rankServicosMaisUtilizados', [$e]);
            return false;
        }
    }
}
