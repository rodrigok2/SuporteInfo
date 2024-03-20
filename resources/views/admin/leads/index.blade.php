@extends('adminlte::page')

@section('title', 'Leads')

@section('content_header')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0">
                    <div class="row">
                        <div style="padding-left: 10px;">
                            Leads
                        </div>
                        <div style="font-size: 14px; vertical-align: middle; padding-left 10px; padding-top: 10px;">
                            <i class="fa fa-info-circle"></i>
                            Utilize para acompanhar a origem dos leads lançados pelo comercial no SUL.
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
                            <form action="{{ route('admin.leads.index') }}" method="GET" >
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
        {{--GRAFICOS DE BARRAS--}}
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" style="overflow-x: auto;">
                        <div class="card-body">
                            <div class="col-sm-12">
                                <div id="columnchart_values" style="display: flex; align-items: center; justify-content: center; width: 900px; height: 300px;"></div>
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
                                    <th style="width: 80%">Origem</th>
                                    <th style="width: 10%">Quantidade</th>
                                    <th style="width: 10%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 0; $i < count($leads); $i++)
                                    <tr>
                                        <td>{{ $leads[$i]['origem'] }}</td>
                                        <td>{{ $leads[$i]['total'] }}</td>
                                        <td>
                                            <form action="{{ route('admin.leads.detalhes') }}" method="GET">
                                                <input type="hidden" name="origem" value="{{ $leads[$i]['origem'] }}">
                                                <input type="hidden" name="data_inicial" value="{{ $data_inicial }}">
                                                <input type="hidden" name="data_final" value="{{ $data_final }}">
                                                <button type=submit class="btn-flat btn-secondary btn-sm">
                                                    <span class="fa-solid fa-magnifying-glass"></span>
                                                    detalhes
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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    @if($leads != null)
        <script type="text/javascript">
            google.charts.load("current", {packages:['corechart']});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ["Origem", "Quantidade", { role: "style" } ],
                    <?php
                        for($i=0; $i < count($leads); $i++){
                            $origem = $leads[$i]["origem"];
                            $total = $leads[$i]["total"];
                            $rand = str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
                            $r = '#'.$rand;

                    ?>
                    ["<?php echo $origem ?>", <?php echo $total ?>, "<?php echo $r ?>"],
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
                    title: "Origem dos leads x quantidade",
                    width: 800,
                    height: 300,
                    bar: {groupWidth: "95%"},
                    legend: { position: "none" },
                };
                var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
                chart.draw(view, options);
            }
        </script>
        <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {

                var data = google.visualization.arrayToDataTable([
                    ['Tipo', 'Quantidade'],
                    ["Contatos ativos", <?php echo $ativos ?>],
                    ["Contatos passivos", <?php echo $passivos ?>],
                ]);

                var options = {
                    title: 'Contatos ativos x contatos passivos'
                };

                var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                chart.draw(data, options);
            }
        </script>
    @endif
@stop



