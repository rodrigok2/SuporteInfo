@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@stop

@section('content')
    @php
        $today = date("Y-m-d");
    @endphp
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card" style="overflow-x: auto;">
                    <div class="card-body">
                        <div class="col-sm-12" style="margin-bottom: 15px; flex-align:row; display: flex; flex-direction: row;">
                            {{--CARD ANDAMENTOS--}}
                            <a class="col-sm-3 remover-sublinhado" href="{{ route('admin.andamentos.index',
                                [
                                    "data_inicial"=> $today,
                                    "data_final"=> $today,
                                    "tecnico_id" => $user->id,
                                    "filtro_ativo"=> 1,
                                ]) }}">
                                <div  style="">
                                    <div class="small-box bg-blue" style="height: 100px;">
                                        <div class="inner">
                                            <div class="title">
                                                <h2>Total de andamentos</h2>
                                            </div>
                                            <div class="valor">
                                                @if($total_andamento[0]->total == null)
                                                    <h6 style="color:#ffffff" id="emprestimos1">0 minutos</h6>
                                                @else
                                                    @php
                                                        $horas = intdiv($total_andamento[0]->total, 60);
                                                        $minutos = $total_andamento[0]->total % 60;
                                                        $texto = "Total: ".$horas." hora(s) e ".$minutos." minuto(s)";
                                                    @endphp
                                                        <h6 style="color:#ffffff" id="emprestimos1">{{ $texto }}</h6>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-volume-control-phone"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            {{--CARD O.S. FECHADAS--}}
                            <a class="col-sm-3 remover-sublinhado" href="{{ route('admin.os.index',
                                [
                                    "tecnico_id"=> $user->id,
                                    "data_fechamento_inicial" => $today,
                                    "data_fechamento_final" => $today,
                                    "status_id"=> 0,
                                    "filtro_ativo"=> 1,
                                ]) }}">
                                <div  style="">
                                    <div class="small-box bg-green" style="height: 100px;">
                                        <div class="inner">
                                            <div class="title">
                                                <h2>O.S. finalizadas</h2>
                                            </div>
                                            <div class="valor">
                                                @if($total_os_fechadas[0]->total == null)
                                                    <h5 style="color:#ffffff" id="emprestimos1">0</h5>
                                                @else
                                                    <h5 style="color:#ffffff" id="emprestimos1">{{ $total_os_fechadas[0]->total }}</h5>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-check-square-o"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            {{--CARD O.S. ABERTAS--}}
                            <a class="col-sm-3 remover-sublinhado" href="{{ route('admin.os.index',
                                [
                                    "tecnico_id"=> $user->id,
                                    "status_id"=> 1,
                                    "filtro_ativo"=> 1,
                                ]) }}">
                                <div  style="">
                                    <div class="small-box bg-red" style="height: 100px;">
                                        <div class="inner">
                                            <div class="title">
                                                <h2>O.S. abertas</h2>
                                            </div>
                                            <div class="valor">
                                                @if($total_os_abertas[0]->total == null)
                                                    <h5 style="color:#ffffff" id="emprestimos1">0</h5>
                                                @else
                                                    <h5 style="color:#ffffff" id="emprestimos1">{{ $total_os_abertas[0]->total }}</h5>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-exclamation-triangle"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
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
                                <th>O.S.</th>
                                <th style="width:10%">Data</th>
                                <th>Responsável</th>
                                <th style="width:20%">Classificacao</th>
                                <th>Descricao</th>
                                <th style="width:10%"></th>
                            </tr>
                        </thead>
                        @if($os_vips == null)
                            <tbody>
                                <td colspan="7">Carregando...</td>
                            </tbody>
                        @else
                            @foreach($os_vips as $item)
                                <tbody>
                                    <tr>
                                        <td>{{ $item->os_id }}</td>
                                        @php
                                            $data_inicial = date('d-m-Y H:m:s', strtotime($item->data_inicial));
                                        @endphp
                                        <td>{{ $data_inicial }}</td>
                                        <td>{{ $item->tecnico }}</td>
                                        <td>{{ $item->classificacao_os }}</td>
                                        <td>{{ $item->descricao }}</td>
                                        <td>
                                            <form action="{{ route('admin.os.detalhes', ["os_id" => $item->os_id]) }}" method="GET">
                                                <button type=submit class="btn-flat btn-secondary btn-sm">
                                                    <span class="fa-solid fa-check"></span>
                                                    Detalhes
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{--GRAFICO DE LINHA--}}
    @if($dadosGrafico != null)
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" style="overflow-x: auto;">
                        <div class="d-flex">
                            <h3 class="card-title" style="margin-top: 3px; margin-bottom: 3px; margin-left: 5px; font-weight: bold;">
                                Percentual de O.S. concluídas em no máximo 24 horas
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="col-sm-12">
                                <div id="curve_chart" height="360"></div>
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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    @if($dadosGrafico != null)
        <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Mes', 'Percentual'],
                    <?php
                        for($i=0; $i < count($dadosGrafico); $i++){
                            $mes = $dadosGrafico[$i]["month"];
                            $percentual = $dadosGrafico[$i]["percentual"];

                    ?>
                    ["<?php echo $mes ?>", <?php echo $percentual ?>],
                    <?php
                    }
                    ?>
                ]
            );

            var options = {
                title: '',
                curveType: 'function',
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            chart.draw(data, options);
        }
        </script>
    @endif
@stop
