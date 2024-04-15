@extends('adminlte::page')

@section('title', 'Rank de servicos')

@section('content_header')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0">
                    <div class="row">
                        <div style="padding-left: 10px;">
                            Conversão de leads
                        </div>
                        <div style="font-size: 14px; vertical-align: middle; padding-left 10px; padding-top: 10px;">
                            <i class="fa fa-info-circle"></i>
                                Pagina para análise da taxa de conversão do leads do departamento comercial.
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
                            <form action="{{ route('admin.leads.conversao') }}" method="GET" >
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
                                            <label for="sistema_id">Sistema: </label>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-select form-select-sm" name="sistema_id" value="">
                                                <option value="">todos</option>
                                                @if($sistemas != null)
                                                    @for($i = 0; $i < count($sistemas); $i++)
                                                        <option value="{{ $sistemas[$i]['sistema_id'] }}">{{ $sistemas[$i]['sistema'] }}</option>
                                                    @endfor
                                                @endif
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
    @if($leads != null)
        {{--GRAFICOS PIZZA--}}
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" style="overflow-x: auto;">
                        <div class="card-body">
                            <div class="col-sm-12">
                                <div id="piechart" style="display: flex; align-items: center; justify-content: center; width: 900px; height: 500px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($leadsConvertidos > 0)
            {{--TABELA LEADS CONVERTIDOS--}}
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card" style="overflow-x: auto;">
                            <div style="text-align: center; font-size: 14px; vertical-align: middle; padding-left 10px; padding-top: 10px;">
                                <i class="fa-solid fa-circle-check"></i>
                                    <strong>Tabela de leads convertidos</strong>
                            </div>
                            <br>
                            <table class="table table-sm m-0">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Origem</th>
                                        <th>Cliente</th>
                                        <th>Data conclusão</th>
                                        <th>Motivo</th>
                                        <th style="width: 10%">Sistema</th>
                                        <th>Vendedor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($i = 0; $i < count($leads); $i++)
                                        @if($leads[$i]['status_id'] == 4)
                                            <tr>
                                                <td>{{ $leads[$i]['lead_id'] }}</td>
                                                <td>{{ $leads[$i]['origin_descricao'] }}</td>
                                                <td>{{ $leads[$i]['cliente'] }}</td>
                                                @php
                                                        $data = date('d-m-Y H:m:s', strtotime($leads[$i]['data_fechado']));
                                                @endphp
                                                <td>{{ $data }}</td>
                                                <td>{{ $leads[$i]['motivo_descricao'] }}</td>
                                                <td>{{ $leads[$i]['sistema'] }}</td>
                                                <td>{{ $leads[$i]['username'] }}</td>
                                            <tr>
                                        @endif
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if($leadsNaoConvertidos > 0)
            {{--TABELA LEADS NÃO CONVERTIDOS--}}
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card" style="overflow-x: auto;">
                            <div style="text-align: center; font-size: 14px; vertical-align: middle; padding-left 10px; padding-top: 10px;">
                                <i class="fa-solid fa-circle-xmark"></i>
                                    <strong>Tabela de leads não convertidos</strong>
                            </div>
                            <br>
                            <table class="table table-sm m-0">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Origem</th>
                                        <th>Cliente</th>
                                        <th>Data conclusão</th>
                                        <th>Motivo</th>
                                        <th style="width: 10%">Sistema</th>
                                        <th>Vendedor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($i = 0; $i < count($leads); $i++)
                                        @if($leads[$i]['status_id'] != 4)
                                            <tr>
                                                <td>{{ $leads[$i]['lead_id'] }}</td>
                                                <td>{{ $leads[$i]['origin_descricao'] }}</td>
                                                <td>{{ $leads[$i]['cliente'] }}</td>
                                                @php
                                                        $data = date('d-m-Y H:m:s', strtotime($leads[$i]['data_fechado']));
                                                @endphp
                                                <td>{{ $data }}</td>
                                                <td>{{ $leads[$i]['motivo_descricao'] }}</td>
                                                <td>{{ $leads[$i]['sistema'] }}</td>
                                                <td>{{ $leads[$i]['username'] }}</td>
                                            <tr>
                                        @endif
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    @if($leads != null)
        <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {

                var data = google.visualization.arrayToDataTable([
                    ['Tipo', 'Quantidade'],
                    ["Convertidos", <?php echo $leadsConvertidos ?>],
                    ["Não convertidos", <?php echo $leadsNaoConvertidos ?>],
                ]);

                var options = {
                    title: 'Leads convertidos x não convertidos'
                };

                var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                chart.draw(data, options);
            }
        </script>
    @endif
@stop



