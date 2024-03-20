@extends('adminlte::page')

@section('title', 'Pesquisar clientes')

@section('content_header')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0">
                    <div class="row">
                        <div style="padding-left: 10px;">
                            Pesquisar contrato cliente prioritário
                        </div>
                        <div style="font-size: 14px; vertical-align: middle; padding-left 10px; padding-top: 10px;">
                            <i class="fa fa-info-circle"></i>
                             Utilize para pesquisar os contratos ativos dos clientes prioritários.
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
                {{--FILTROS DE PESQUISA--}}
                <div class="card" style="overflow-x: auto;">
                    <div class="card-body">
                        <div class="col-md-12 text-right">
                            <form action="{{ route('admin.vip.pesquisar') }}" method="GET" >
                                <div class="form-group">
                                    <label for="cliente_id">Nº Cliente: </label>
                                    <input style="width: 17%" name="cliente_id"  id="cliente_id" type="text"/>
                                    <label for="contrato_id">Nº Contrato: </label>
                                    <input style="width: 16%" name="contrato_id" id="contrato_id" type="text"/>
                                    <label for="cnpj">CNPJ: </label>
                                    <input style="width: 40%" name="cnpj" id="cnpj" type="text"/>
                                </div>
                                <div class="form-group">
                                    <label for="fantasia">Fantasia:</label>
                                    <input style="width: 30%" name="fantasia"  id="fantasia" type="text"/>
                                    <label for="razao_social">Razão Social:</label>
                                    <input style="width: 50%" name="razao_social" id="razao_social" type="text"/>
                                </div>
                                <div class="form-group">
                                    <div class="form-group text-right">
                                        <input type="hidden" name="filtro_ativo" value="1">
                                        <button type=submit class="btn-flat btn-primary">
                                            <span class="fa-solid fa-magnifying-glass"></span>
                                            Pesquisar
                                        </button>
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
    @if($clientes != null)
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" style="overflow-x: auto;">
                        <table class="table table-sm m-0">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Razão Social</th>
                                    <th>Fantasia</th>
                                    <th>CNPJ</th>
                                    <th>Contrato</th>
                                    <th>Sistema</th>
                                    <th>Status</th>
                                    <th style="width:10%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 0; $i < count($clientes); $i++)
                                    <tr>
                                        <td>{{ $clientes[$i]['cliente_id'] }}</td>
                                        <td>{{ $clientes[$i]['razao_social'] }}</td>
                                        <td>{{ $clientes[$i]['fantasia'] }}</td>
                                        <td>{{ $clientes[$i]['cnpj'] }}</td>
                                        <td>{{ $clientes[$i]['contrato_id'] }}</td>
                                        <td>{{ $clientes[$i]['sistema'] }}</td>
                                        <td>{{ $clientes[$i]['contrato_status'] }}</td>
                                        <td>
                                            <form action="{{ route('admin.vip.cadastrar') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="contrato" value="{{ $clientes[$i]['contrato_id'] }}">
                                                <button type=submit class="btn-flat btn-secondary btn-sm">
                                                    <span class="fa-solid fa-check"></span>
                                                    Cadastrar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
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
@stop



