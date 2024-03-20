@extends('adminlte::page')

@section('title', 'Ordens de servicos')

@section('content_header')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0">
                    <div class="row">
                        <div style="padding-left: 10px;">
                            Ordens de serviços
                        </div>
                        <div style="font-size: 14px; vertical-align: middle; padding-left 10px; padding-top: 10px;">
                            <i class="fa fa-info-circle"></i>
                             Utilize para pesquisar e visualizar as ordens de serviços lançadas no SUL.
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
                            <form action="{{ route('admin.os.index') }}" method="GET" >
                                <button type=submit class="btn-flat btn-dark btn-toggle" data-element="#minhaDiv">
                                    <span class="fa-solid fa-magnifying-glass-plus"></span>
                                    Mais filtros
                                </button>
                                <div class="text-center" id="minhaDiv" style="display:none">
                                    <hr>
                                    <div class="form-group">
                                        <h6 class="text-center"><b>Filtro de clientes:</b></h6>
                                    </div>
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
                                    <hr>
                                    <div class="form-group">
                                        <h6 class="text-center"><b>Classificações:</b></h6>
                                    </div>
                                    <div class="form-group">
                                        <div class="row text-left">
                                            @foreach ($classificacoes as $item)
                                                <div class="col-md-4">
                                                    <label style="font-size: 13px;">
                                                        <input type="checkbox" name="classificacao_id[]" value="{{ $item->classificacao_id }}">
                                                        {{ $item->classificacao_os }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                <div class="text-center">
                                    <div class="form-group">
                                        <h6 class="text-center"><b>Outros filtros:</b></h6>
                                    </div>
                                    <div class="form-group">
                                        <label for="data_abertura_inicial">Aberta início: </label>
                                        <input style="width: 14%" name="data_abertura_inicial" id="data_abertura_inicial" type="date"/>
                                        <label for="data_abertura_final">Aberta fim: </label>
                                        <input style="width: 14%" name="data_abertura_final" id="data_abertura_final" type="date"/>
                                        <label for="data_fechamento_inicial">Fechada início: </label>
                                        <input style="width: 14%" name="data_fechamento_inicial" id="data_fechamento_inicial" type="date"/>
                                        <label for="data_fechamento_final">Fechada fim: </label>
                                        <input style="width: 14%" name="data_fechamento_final" id="data_fechamento_final" type="date"/>
                                    </div>
                                    <div class="form-group">
                                        <div class="row text-right">
                                            <div class="col-md-1">
                                                <label for="tecnico_id">Técnico: </label>
                                            </div>
                                            <div class="col-md-2">
                                                @if($tecnicos != null)
                                                    <select class="form-select form-select-sm" name="tecnico_id" value="">
                                                        <option value="">todos</option>
                                                        @for($i = 0; $i < count($tecnicos); $i++)
                                                            <option value="{{ $tecnicos[$i]['user_id'] }}">{{ $tecnicos[$i]['username'] }}</option>
                                                        @endfor
                                                    </select>
                                                @endif
                                            </div>
                                            <div class="col-md-1">
                                                <label for="prioridade_id">Prioridade: </label>
                                            </div>
                                            <div class="col-md-2">
                                                @if($prioridades != null)
                                                    <select class="form-select form-select-sm" name="prioridade_id" value="">
                                                        <option value="">todos</option>
                                                        @for($i = 0; $i < count($prioridades); $i++)
                                                            <option value="{{ $prioridades[$i]['prioridade_id'] }}">{{ $prioridades[$i]['prioridade_descricao'] }}</option>
                                                        @endfor
                                                    </select>
                                                @endif
                                            </div>
                                            <div class="col-md-1">
                                                <label for="status_id">Status: </label>
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-select form-select-sm" name="status_id" value="">
                                                    <option value="">todos</option>
                                                    <option value="1">Aberta</option>
                                                    <option value="0">Fechada</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="squad_id">Squads: </label>
                                            </div>
                                            <div class="col-md-2">
                                                @if($squads != null)
                                                    <select class="form-select form-select-sm" name="squad_id" value="">
                                                        <option value="">todos</option>
                                                        @for($i = 0; $i < count($squads); $i++)
                                                            <option value="{{ $squads[$i]['squad_id'] }}">{{ $squads[$i]['squad'] }}</option>
                                                        @endfor
                                                    </select>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group text-left">
                                        <label for="os_id">O.S.: </label>
                                        <input style="width: 10%" name="os_id"  id="os_id" type="text"/>
                                    </div>
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
    @if($os_detalhes != null)
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" style="overflow-x: auto;">
                        <table class="table table-sm m-0">
                            <thead>
                                <tr>
                                    <th>O.S.</th>
                                    <th style="width:10%">Abertura</th>
                                    <th>Técnico</th>
                                    <th style="width:20%">Classificação</th>
                                    <th>Descrição</th>
                                    <th>Status</th>
                                    <th style="width:10%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($os_detalhes as $item)
                                    <tr>
                                        <td>{{ $item->os_id }}</td>
                                        @php
                                            $data_inicial = date('d-m-Y H:m:s', strtotime($item->data_inicial));
                                        @endphp
                                        <td>{{ $data_inicial }}</td>
                                        <td>{{ $item->tecnico }}</td>
                                        <td>{{ $item->classificacao_os }}</td>
                                        <td>{{ $item->descricao }}</td>
                                        @if ($item->os_status == 0)
                                            <td>Fechada</td>
                                        @else
                                            <td>Aberta</td>
                                        @endif
                                        <td>
                                            <form action="{{ route('admin.os.detalhes', ["os_id" => $item->os_id]) }}" method="GET">
                                                <button type=submit class="btn-flat btn-secondary btn-sm">
                                                    <span class="fa-solid fa-check"></span>
                                                    Detalhes
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="container">
                            <div class="row">
                                <div class="col-8">
                                    {{ $os_detalhes->appends(request()->input())->links() }}
                                </div>
                                <div class="col-4 text-right">
                                    Total de registros: <b> {{ $os_detalhes->total() }} </b>
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


