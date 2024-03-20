<?php

namespace App\Repositories\SUL;

use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Repositories\SUL\ServicosRepository;
use App\Repositories\SUL\ServicosSubgruposRepository;
use App\Repositories\SUL\ServicosGruposRepository;
use App\Repositories\SUL\UsersRepository;
use App\Repositories\SUL\SisOsTiposRepository;

class SisOsAndamentosRepository{

    function AndamentosPorDatas($data_inicial, $data_final){
        try{
            $list = DB::connection('mysql_sul')->table("sis_os_andamento")
                ->select("sis_os_andamento.servico as servico_id", DB::raw('count(sis_os_andamento.servico) as total'))
                ->where("sis_os_andamento.data", ">=", $data_inicial." 00:00:00")
                ->where("sis_os_andamento.data", "<=", $data_final." 23:59:59")
                ->limit(20)
                ->orderBy("total","desc")
                ->groupBy("servico_id")
                ->get();

            //Converte o resultado da consulta no banco em Array
            $list = json_decode(json_encode($list), true);

            //cria instância da classe ServicoRepository
            $servicosRepository = new ServicosRepository();

            //cria instância da classe ServicosSubgruposRepository
            $servicosSubgruposRepository = new ServicosSubgruposRepository();

            //cria instância da classe ServicosGruposRepository
            $servicosGruposRepository = new ServicosGruposRepository();

            //Pesquisa a descricao do servico e o subgrupo no SUL
            for($i = 0; $i < count($list); $i++){
                //Pesquisa o servico no SUL e grava no array $list
                $servico = $servicosRepository->ServicoPorId($list[$i]['servico_id']);
                $list[$i]['descricao_servico'] = $servico[0]['descricao_servico'];

                //Pesquisa o subgrupo do servico no SUL e grava no array $list
                $subgrupo = $servicosSubgruposRepository->SubGruposPorId($servico[0]['subgrupo_id']);
                $list[$i]['descricao_subgrupo'] = $subgrupo[0]['descricao_subgrupo'];

                //Pesquisa o grupo do servico no SUL e grava no array $list
                $grupo = $servicosGruposRepository->GrupoPorId($subgrupo[0]['grupo_id']);
                $list[$i]['descricao_grupo'] = $grupo[0]['descricao_grupo'];
            }

            return $list;
        }catch(Exception $e){
            Log::critical('SisOsAndamentoRepository->AndamentosPorDatas', [$e]);
            return false;
        }
    }

    function AndamentoPorServico($data_inicial, $data_final, $servico_id){
        try{
            //Busca no SUl os andamentos vinculados ao servico e o periodo selecionado
            $list = DB::connection('mysql_sul')->table("sis_os_andamento")
                ->select(
                    "sis_os_andamento.id as andamento_id",
                    "sis_os_andamento.os as os_id",
                    "sis_os_andamento.usuario as tecnico_id",
                    "sis_os_andamento.status as classificacao_os_id",
                    "sis_os_andamento.info as descricao",
                    "sis_os_andamento.data as data",
                    "sis_os_andamento.tempo_execucao as duracao",
                    "sis_os_andamento.situacao as status"
                    )
                ->where("sis_os_andamento.data", ">=", $data_inicial." 00:00:00")
                ->where("sis_os_andamento.data", "<=", $data_final." 23:59:59")
                ->where("sis_os_andamento.servico", "=", $servico_id)
                ->orderBy("os_id","asc")
                ->get();

            $list = json_decode(json_encode($list), true);

            //Cria instancia da Classe UsersRepository para consultar dados dos técnicos
            $usersRepositoy = new UsersRepository();

            //Cria instancia da Classe SisOsTiposRepositoy para consultar os tipos da O.S.
            $sisOsTiposRepository = new SisOsTiposRepository();

            for($i = 0; $i < count($list); $i++){

                //Pesquisa os dados do técnico
                $tecnico = $usersRepositoy->UsuarioPorId($list[$i]['tecnico_id']);
                $list[$i]['username'] = $tecnico[0]['username'];

                //Pesquisa a classificacao da OS
                $classificacao = $sisOsTiposRepository->TipoOsPorId($list[$i]['classificacao_os_id']);
                $list[$i]['classificacao_os'] = $classificacao[0]['classificacao_os'];
            }

            return $list;

        }catch(Exception $e){
            Log::critical('SisOsAndamentoRepository->AndamentoPorServico', [$e]);
            return false;
        }
    }

    function andamentoPorOsId($os_id){
        try{
            $list = DB::connection('mysql_sul')->table("sis_os_andamento")
                ->select(
                    "sis_os_andamento.id as andamento_id",
                    "sis_os_andamento.usuario as tecnico_id",
                    "sis_os_andamento.status as classificacao_os_id",
                    "sis_os_andamento.info as descricao",
                    "sis_os_andamento.data as data",
                    "sis_os_andamento.tempo_execucao as tempo_execucao",
                    "sis_os_andamento.situacao as status",
                    )
                ->where("sis_os_andamento.os", "=", $os_id)
                ->orderBy("andamento_id","asc")
                ->get();

            //Converte o resultado da consulta no banco em Array
            $list = json_decode(json_encode($list), true);

            //Cria instancia da Classe UsersRepository para consultar dados dos técnicos
            $usersRepositoy = new UsersRepository();

            //Cria instancia da Classe TipoOrdemServicoRepository para consultar os tipos da O.S.
            $sisOsTiposRepository = new SisOsTiposRepository();

            for($i = 0; $i < count($list); $i++){
                //Pesquisa os dados do técnico
                $tecnico = $usersRepositoy->UsuarioPorId($list[$i]['tecnico_id']);
                $list[$i]['username'] = $tecnico[0]['username'];

                //Pesquisa a classificacao da OS
                $classificacao = $sisOsTiposRepository->TipoOsPorId($list[$i]['classificacao_os_id']);
                $list[$i]['classificacao_os'] = $classificacao[0]['classificacao_os'];
            }

            return $list;

        }catch(Exception $e){
            Log::critical('SisOsAndamentoRepository->andamentoPorOsId', [$e]);
            return false;
        }
    }

    function AndamentoPorTecnico($data_inicial, $data_final, $tecnico_id){
        try{
            //Busca no SUl os andamentos vinculados ao servico e o periodo selecionado
            $list = DB::connection('mysql_sul')->table("sis_os_andamento")
                ->select(
                    "sis_os_andamento.id as andamento_id",
                    "sis_os_andamento.os as os_id",
                    "sis_os_andamento.usuario as tecnico_id",
                    "sis_os_andamento.status as classificacao_os_id",
                    "sis_os_andamento.info as descricao",
                    "sis_os_andamento.data as data",
                    "sis_os_andamento.tempo_execucao as duracao",
                    "sis_os_andamento.situacao as status"
                    )
                ->where("sis_os_andamento.data", ">=", $data_inicial." 00:00:00")
                ->where("sis_os_andamento.data", "<=", $data_final." 23:59:59")
                ->where("sis_os_andamento.usuario", "=", $tecnico_id)
                ->orderBy("os_id","asc")
                ->get();

            $list = json_decode(json_encode($list), true);

            //Cria instancia da Classe UsersRepository para consultar dados dos técnicos
            $usersRepositoy = new UsersRepository();

            //Cria instancia da Classe SisOsTiposRepositoy para consultar os tipos da O.S.
            $sisOsTiposRepository = new SisOsTiposRepository();

            for($i = 0; $i < count($list); $i++){

                //Pesquisa os dados do técnico
                $tecnico = $usersRepositoy->UsuarioPorId($list[$i]['tecnico_id']);
                $list[$i]['username'] = $tecnico[0]['username'];

                //Pesquisa a classificacao da OS
                $classificacao = $sisOsTiposRepository->TipoOsPorId($list[$i]['classificacao_os_id']);
                $list[$i]['classificacao_os'] = $classificacao[0]['classificacao_os'];
            }

            return $list;

        }catch(Exception $e){
            Log::critical('SisOsAndamentoRepository->AndamentoPorTecnico', [$e]);
            return false;
        }
    }

    function TempoTotalPorTecnico($data_inicial, $data_final, $tecnico_id){
        try{
            //Busca no SUl os andamentos vinculados ao servico e o periodo selecionado
            $list = DB::connection('mysql_sul')->table("sis_os_andamento")
                ->select(
                    DB::raw('sum(sis_os_andamento.tempo_execucao) as total')
                    )
                ->where("sis_os_andamento.data", ">=", $data_inicial." 00:00:00")
                ->where("sis_os_andamento.data", "<=", $data_final." 23:59:59")
                ->where("sis_os_andamento.usuario", "=", $tecnico_id)
                ->get();

            return $list;

        }catch(Exception $e){
            Log::critical('SisOsAndamentoRepository->TempoTotalPorTecnico', [$e]);
            return false;
        }
    }
}
