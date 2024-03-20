@extends('adminlte::page')

@section('title', 'Logs')

@section('content_header')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0">
                    <div class="row">
                        <div style="padding-left: 10px;">
                            Logs de backups
                        </div>
                        <div style="font-size: 14px; vertical-align: middle; padding-left 10px; padding-top: 10px;">
                            <i class="fa fa-info-circle"></i>
                            Utilize para acompanhar os logs gravados pelo LSoft Backup, quando o sistema apresenta tela erro informando ao cliente que não foi possível realizar o backup FTP.
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
                            <form action="{{ route('admin.logs.index') }}" method="GET" >
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-1">
                                            <label class="date-label" for="cnpj">CNPJ</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="cnpj" id="cnpj" value=""/>
                                        </div>
                                        <div class="col-md-6">
                                        </div>
                                        <div class="col-md-2 text-right">
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
    @if($logs != null)
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" style="overflow-x: auto;">
                        <table class="table table-sm m-0">
                            <thead>
                                <tr>
                                    <th style="width:10%">Data</th>
                                    <th style="width:15%">CNPJ</th>
                                    <th>Descricao</th>
                                    <th>Nome PC</th>
                                    <th>Pasta</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $item)
                                    <tr>
                                        @php
                                            $data = date('d-m-Y H:m:s', strtotime($item->data));
                                        @endphp
                                        <td>{{ $data }}</td>
                                        <td>{{ $item->cnpj }}</td>
                                        <td>{{ $item->descricao }}</td>
                                        <td>{{ $item->computador }}</td>
                                        <td>{{ $item->pasta }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="container">
                            <div class="row">
                                <div class="col-8">
                                    {{ $logs->appends(request()->input())->links() }}
                                </div>
                                <div class="col-4 text-right">
                                    Total de registros: <b> {{ $logs->total() }} </b>
                                </div>
                            </div>
                        </div>
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
@stop



