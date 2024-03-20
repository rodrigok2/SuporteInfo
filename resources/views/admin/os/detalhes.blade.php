@extends('adminlte::page')

@section('title', 'Detalhes da ordem de serviço')

@section('content_header')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0">
                    <div class="row">
                        <div style="padding-left: 10px;">
                            Detalhes da ordem de servico
                        </div>
                        <div style="font-size: 14px; vertical-align: middle; padding-left 10px; padding-top: 10px;">
                            <i class="fa fa-info-circle"></i>
                                Utilize para visualizar os detalhes referente à ordem de serviço do SUL.
                        </div>
                    </div>
                </h1>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card" style="overflow-x: auto;">
                    <div class="card-body">
                        <div class="col-sm-12">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th scope="row" style="width: 15%">Numero da O.S.</th>
                                        <td style="width: 10%">{{ $os_detalhes[0]["os_id"] }}</td>
                                        <th scope="row" style="width: 15%">Status da O.S.</th>
                                        @if ($os_detalhes[0]["os_status"] === 1)
                                            <td style="width: 10%">Aberta</td>
                                        @else
                                            <td style="width: 10%">Fechada</td>
                                        @endif
                                        <th scope="row" style="width: 15%">Data abertura</th>
                                        @php
                                            $data_inicial = date('d-m-Y H:m:s', strtotime($os_detalhes[0]["data_inicial"]));
                                        @endphp
                                        <td style="width: 15%">{{ $data_inicial }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" style="width: 15%">Numero contrato</th>
                                        <td style="width: 10%">{{ $os_detalhes[0]["contrato_id"] }}</td>
                                        <th scope="row" cstyle="width: 15%">Status do contrato</th>
                                        <td style="width: 10%">{{ $os_detalhes[0]["contrato_status"] }}</td>
                                        <th scope="row" style="width: 15%">Data fechamento</th>
                                        @php
                                            $data_final = date('d-m-Y H:m:s', strtotime($os_detalhes[0]["data_final"]));
                                        @endphp
                                        @if ($os_detalhes[0]["os_status"] === 1)
                                            <td style="width: 15%"></td>
                                        @else
                                            <td style="width: 15%">{{ $data_final }}</td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th scope="row" style="width: 20%">Cliente</th>
                                        <td style="width: 30%">{{ $os_detalhes[0]["razao_social"] }}</td>
                                        <th scope="row" style="width: 20%">Nome fantasia</th>
                                        <td style="width: 30%">{{ $os_detalhes[0]["fantasia"] }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" style="width: 20%">CNPJ</th>
                                        <td style="width: 30%">{{ $os_detalhes[0]["cnpj"] }}</td>
                                        <th scope="row" style="width: 20%">Solicitante</th>
                                        <td style="width: 30%">{{ $os_detalhes[0]["solicitante_nome"] }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" style="width: 20%">CPF do solicitante</th>
                                        <td style="width: 30%">{{ $os_detalhes[0]["solicitante_cpf"] }}</td>
                                        <th scope="row" style="width: 20%">E-mail do solicitante</th>
                                        <td style="width: 30%">{{ $os_detalhes[0]["solicitante_email"] }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" style="width: 20%">Telefone do solicitante</th>
                                        <td style="width: 30%">{{ $os_detalhes[0]["solicitante_telefone"] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th scope="row" style="width: 20%">Classificação da O.S.</th>
                                        <td style="width: 30%">{{ $os_detalhes[0]["classificacao_os"] }}</td>
                                        <th scope="row" style="width: 20%">Prioridade da O.S.</th>
                                        @if ($os_detalhes[0]["prioridade_os"] == 'Baixa')
                                            <td style="width: 30%; background-color: green"><b>{{ $os_detalhes[0]["prioridade_os"] }}</b></td>
                                        @elseif($os_detalhes[0]["prioridade_os"] == 'Normal')
                                            <td style="width: 30%; background-color: yellow"><b>{{ $os_detalhes[0]["prioridade_os"] }}</b></td>
                                        @elseif($os_detalhes[0]["prioridade_os"] == 'Alta')
                                            <td style="width: 30%; background-color: orange"><b>{{ $os_detalhes[0]["prioridade_os"] }}</b></td>
                                        @elseif($os_detalhes[0]["prioridade_os"] == 'Urgente')
                                            <td style="width: 30%; background-color: red"><b>{{ $os_detalhes[0]["prioridade_os"] }}</b></td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th scope="row" style="width: 20%">Técnico</th>
                                        <td style="width: 30%"><b>{{ $os_detalhes[0]["username"] }}</b></td>
                                        <th scope="row" style="width: 20%">Tempo total</th>
                                        @if($os_detalhes[0]["tempo_total"] == null)
                                            <td style="width: 30%"><b>tempo total não cadastrado</b></td>
                                        @else
                                            <td style="width: 30%"><b>{{ $os_detalhes[0]["tempo_total"] }} minutos</b></td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th scope="row" style="width: 10%">Descrição</th>
                                        <td style="width: 90%">{{ $os_detalhes[0]["descricao"] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--TABELA--}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card" style="overflow-x: auto;">
                    <table class="table table-sm m-0">
                        <thead>
                            <tr>
                                <th>Protocolo</th>
                                <th style="width:10%">Data</th>
                                <th style="width:20%">Classificacao</th>
                                <th>Descricao</th>
                                <th>Técnico</th>
                                <th>Status</th>
                                <th style="width:10%">Duração</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i = 0; $i < count($andamentos_os); $i++)
                                <tr>
                                    <td>{{ $andamentos_os[$i]['andamento_id'] }}</td>
                                    @php
                                        $data_inicial = date('d-m-Y H:m:s', strtotime($andamentos_os[$i]['data']));
                                    @endphp
                                    <td>{{ $data_inicial }}</td>
                                    <td>{{ $andamentos_os[$i]['classificacao_os'] }}</td>
                                    <td>{{ $andamentos_os[$i]['descricao'] }}</td>
                                    <td>{{ $andamentos_os[$i]['username'] }}</td>
                                    @if ($andamentos_os[$i]['status'] == 'F')
                                        <td>Fechado</td>
                                    @else
                                        <td>Aberto</td>
                                    @endif
                                    <td>{{ $andamentos_os[$i]['tempo_execucao'] }} minutos</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <strong>Desenvolvido por Rodrigo Martins</strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versão</b>
         0.01
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

@stop

@section('js')
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/ec15bfe7f6.js" crossorigin="anonymous"></script>
@stop




