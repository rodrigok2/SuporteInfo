@extends('adminlte::page')

@section('title', 'Lista de andamentos')

@section('content_header')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0">
                    <div class="row">
                        <div style="padding-left: 10px;">
                            Andamentos em ordens de serviços
                        </div>
                        <div style="font-size: 14px; vertical-align: middle; padding-left 10px; padding-top: 10px;">
                            <i class="fa fa-info-circle"></i>
                            Utilize para acompanhar os andamentos dados nas ordens de serviços pelos técnicos do suporte.
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
                {{--MSG DE ERRO--}}
                <div class="row container" id="foo">
                    @if($mensagem = Session::get('erro'))
                        <div class="row">
                            <div class="msg-alertas">
                                <span class="ms erro"><i class="icon icon-hand-paper-o"></i> {{ $mensagem }}</span>
                        </div>
                        </div>
                    @endif
                </div>
                <div class="card" style="overflow-x: auto;">
                    <div class="card-body">
                        <div class="col-sm-12">
                            <form action="{{ route('admin.andamentos.index') }}" method="GET" >
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-1">
                                            <label for="data_inicial">Início: </label>
                                        </div>
                                        <div class="col-md-2">
                                            <input name="data_inicial" id="data_inicial" type="date"/>
                                        </div>
                                        <div class="col-md-1">
                                            <label for="data_final">Início: </label>
                                        </div>
                                        <div class="col-md-2">
                                            <input name="data_final" id="data_final" type="date"/>
                                        </div>
                                        <div class="col-md-1">
                                            <label for="tecnico_id">Técnico: </label>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-select form-select-sm" name="tecnico_id" value="">
                                                <option value="">todos</option>
                                                @for($i = 0; $i < count($tecnicos); $i++)
                                                    <option value="{{ $tecnicos[$i]['user_id'] }}">{{ $tecnicos[$i]['username'] }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="hidden" name="filtro_ativo" value="1">
                                            <button type=submit class="btn-flat btn-primary">
                                                <span class="fa-solid fa-magnifying-glass"></span>
                                                Pesquisar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--TABELA--}}
    @if($andamentos != null)
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" style="overflow-x: auto;">
                        <table class="table table-sm m-0">
                            <thead>
                                <tr>
                                    <th style="width:10%">Data</th>
                                    <th>Técnico</th>
                                    <th>Descrição</th>
                                    <th style="width:20%">Classificação</th>
                                    <th>Duração</th>
                                    <th>Status</th>
                                    <th style="width:10%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 0; $i < count($andamentos); $i++)
                                    <tr>
                                        @php
                                                $data = date('d-m-Y H:m:s', strtotime($andamentos[$i]['data']));
                                        @endphp
                                        <td>{{ $data }}</td>
                                        <td>{{ $andamentos[$i]['username'] }}</td>
                                        <td>{{ $andamentos[$i]['descricao'] }}</td>
                                        <td>{{ $andamentos[$i]['classificacao_os'] }}</td>
                                        <td>{{ $andamentos[$i]['duracao'] }} minutos</td>
                                        @php
                                            $status_andamento = '';
                                            if($andamentos[$i]['status'] == 'P'){
                                                $status_andamento = 'Pausado';
                                            }
                                            elseif($andamentos[$i]['status'] == 'A'){
                                                $status_andamento = 'Aberto';
                                            }
                                            elseif($andamentos[$i]['status'] == 'F'){
                                                $status_andamento = 'Fechado';
                                            }
                                        @endphp
                                        <td>{{ $status_andamento }}</td>

                                        <td>
                                            <form action="{{ route('admin.os.detalhes', ["os_id" => $andamentos[$i]['os_id']]) }}" method="GET">
                                                <button type=submit class="btn-flat btn-secondary btn-sm">
                                                    <span class="fa-solid fa-magnifying-glass"></span>
                                                    {{ $andamentos[$i]['os_id'] }}
                                                </button>
                                            </form>
                                        </td>
                                    <tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
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
    <script>
        // Iniciará quando todo o corpo do documento HTML estiver pronto.
        $().ready(function() {
	        setTimeout(function () {
		        $('#foo').hide(); // "foo" é o id do elemento que seja manipular.
	        }, 7000); // O valor é representado em milisegundos.
        });
    </script>
    <script>
        $(function() {
            $(".btn-toggle").click(function(e) {
                e.preventDefault();
                el = $(this).data('element');
                $(el).toggle();
            });
        });
    </script>
@stop


