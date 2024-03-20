@extends('adminlte::page')

@section('title', 'Clientes Vips')

@section('content_header')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0">
                    <div class="row">
                        <div style="padding-left: 10px;">
                            Clientes prioritários
                        </div>
                        <div style="font-size: 14px; vertical-align: middle; padding-left 10px; padding-top: 10px;">
                            <i class="fa fa-info-circle"></i>
                             Utilize para cadastrar clientes que precisam de atendimento prioritários no suporte LSoft.
                             O sistema enviará e-mails automáticos ao helpdesk sempre que for aberta uma nova ordem de serviço
                             para os clientes cadastrados
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
                    @if($mensagem = Session::get('sucesso'))
                        <div class="row">
                            <div class="msg-alertas">
                                <span class="ms ok"><i class="icon icon-users-1"></i> {{ $mensagem }}</span>
                        </div>
                        </div>
                    @endif
                    @if($mensagem = Session::get('info'))
                        <div class="row">
                            <div class="msg-alertas">
                                <span class="ms alerta"><i class="icon icon-alert-1"></i> {{ $mensagem }}</span>
                        </div>
                        </div>
                    @endif
                </div>
                {{--BOTAO CADASTRAR--}}
                <div class="card" style="overflow-x: auto;">
                    <div class="card-body">
                        <div class="col-sm-12 text-right">
                            <form action="{{ route('admin.vip.pesquisar') }}" method="GET">
                                <button type=submit class="btn-flat btn-primary">
                                    <span class="fa-solid fa-user-plus"></span>
                                    Cadastrar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--TABELA--}}
    @if($vips != null)
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
                                    <th style="width: 15%">CNPJ</th>
                                    <th>Contrato</th>
                                    <th>Status</th>
                                    <th>Ultima O.S.</th>
                                    <th style="width: 10%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 0; $i < count($vips); $i++)
                                    <tr>
                                        <td>{{ $vips[$i]['cliente_id'] }}</td>
                                        <td>{{ $vips[$i]['razao_social'] }}</td>
                                        <td>{{ $vips[$i]['fantasia'] }}</td>
                                        <td>{{ $vips[$i]['cnpj'] }}</td>
                                        <td>{{ $vips[$i]['contrato_id'] }}</td>
                                        <td>{{ $vips[$i]['contrato_status'] }}</td>
                                        <td>{{ $vips[$i]['ultima_os'] }}</td>
                                        <td>
                                            <form action="{{ route('admin.vip.excluir', $vips[$i]['vip_id']) }}" method="POST" id="deletar-registro">
                                                @csrf
                                                @method('delete')
                                                <button type=submit class="btn-flat btn-danger btn-sm">
                                                    <span class="fa-regular fa-trash-can"></span>
                                                    Apagar
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



