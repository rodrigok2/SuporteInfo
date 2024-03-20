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
                            Rank de serviços
                        </div>
                        <div style="font-size: 14px; vertical-align: middle; padding-left 10px; padding-top: 10px;">
                            <i class="fa fa-info-circle"></i>
                             Utilize para pesquisar e visualizar os 20 serviços mais utilizados para classificar as O.S. do SUL.
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
                        <div class="col-sm-12">
                            <form action="{{ route('admin.servicos.index') }}" method="GET" >
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
                                        <div class="col-md-4">
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
    {{--GRAFICOS DE BARRAS--}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card" style="overflow-x: auto;">
                    <div class="card-body">
                        <div class="col-sm-12">
                            <div id="columnchart_values" style="display: flex; align-items: center; justify-content: center; width: 1000px; height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--TABELA--}}
    @if($lista_de_servicos != null)
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" style="overflow-x: auto;">
                        <table class="table table-sm m-0">
                            <thead>
                                <tr>
                                    <th>Grupo</th>
                                    <th>Subgrupo</th>
                                    <th>Serviço</th>
                                    <th>Total</th>
                                    <th style="width:10%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 0; $i < count($lista_de_servicos); $i++)
                                    <tr>
                                        <td>{{ $lista_de_servicos[$i]['descricao_grupo'] }}</td>
                                        <td>{{ $lista_de_servicos[$i]['descricao_subgrupo'] }}</td>
                                        <td>{{ $lista_de_servicos[$i]['descricao_servico'] }}</td>
                                        <td>{{ $lista_de_servicos[$i]['total'] }}</td>
                                        <td>
                                            <form action="{{ route('admin.servicos.detalhes') }}" method="GET">
                                                <input type="hidden" name="data_inicial" value="{{ $data_inicial }}">
                                                <input type="hidden" name="data_final" value="{{ $data_final }}">
                                                <input type="hidden" name="servico_id" value="{{ $lista_de_servicos[$i]['servico_id'] }}">
                                                <input type="hidden" name="descricao_grupo" value="{{ $lista_de_servicos[$i]['descricao_grupo'] }}">
                                                <input type="hidden" name="descricao_subgrupo" value="{{ $lista_de_servicos[$i]['descricao_subgrupo'] }}">
                                                <input type="hidden" name="descricao_servico" value="{{ $lista_de_servicos[$i]['descricao_servico'] }}">
                                                <button type=submit class="btn-flat btn-secondary btn-sm">
                                                    <span class="fa-solid fa-check"></span>
                                                    Detalhes
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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    @if($lista_de_servicos != null)
        <script type="text/javascript">
            google.charts.load("current", {packages:['corechart']});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ["Servico", "Quantidade", { role: "style" } ],
                    <?php
                        for($i = 0; $i < count($lista_de_servicos); $i++){
                            $servico = $lista_de_servicos[$i]["descricao_servico"];
                            $total = $lista_de_servicos[$i]["total"];
                            $rand = str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
                            $r = '#'.$rand;

                    ?>
                    ["<?php echo $servico ?>", <?php echo $total ?>, "<?php echo $r ?>"],
                    <?php
                        }
                    ?>
                ]);

                var view = new google.visualization.DataView(data);
                view.setColumns([0, 1, {
                    calc: "stringify",
                    sourceColumn: 1,
                    type: "string",
                    role: "annotation" },
                2]);

                var options = {
                    title: "Servicos x quantidade",
                    width: 1000,
                    height: 500,
                    bar: {groupWidth: "95%"},
                    legend: { position: "none" },
                };
                var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
                chart.draw(view, options);
            }
        </script>
    @endif
@stop


