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
                            Rank de produtividade dos técnicos
                        </div>
                        <div style="font-size: 14px; vertical-align: middle; padding-left 10px; padding-top: 10px;">
                            <i class="fa fa-info-circle"></i>
                                Pagina utilizada para comparar a produtividade da equipe de suporte técnico.
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
                            <form action="{{ route('admin.produtividade.teste') }}" method="GET" >
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
    {{--TABELA--}}
    @if($tecnicos != null)
        @php
            $usuarioTemOsConcluida = 'false';
            for($i=0; $i < count($tecnicos); $i++){
                if($username == $tecnicos[$i]['username'])
                {
                    $usuarioTemOsConcluida = 'true';
                }
            }
        @endphp
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    @if($usuarioTemOsConcluida)
                        {{--GRAFICOS DE BARRAS--}}
                        <div id="columnchart_values" style="display: flex; align-items: center; justify-content: center; width: 350px; height: 500px;"></div>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="card" style="overflow-x: auto;">
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover grid-table">
                                <thead>
                                    <tr>
                                        <th>Posição</th>
                                        <th>Username</th>
                                        <th>MMR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($i = 0; $i < count($tecnicos); $i++)
                                        <tr>
                                            @php
                                                $x = $i + 1;
                                            @endphp
                                            <td>{{ $x }}º</td>
                                            <td>{{ $tecnicos[$i]['username'] }}</td>
                                            @php
                                                $mmr = round($tecnicos[$i]['mmr'] , 1);
                                            @endphp
                                            <td>{{ $mmr }}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
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
    @if($tecnicos != null)
        @if($usuarioTemOsConcluida)
            @php
                $nivel1 = 'Nível 1';
                $nivel2 = 'Nível 2';
                $nivel3 = 'Nível 3';
            @endphp
            <script type="text/javascript">
                google.charts.load("current", {packages:['corechart']});
                google.charts.setOnLoadCallback(drawChart);
                function drawChart() {
                    var data = google.visualization.arrayToDataTable([
                        ["Nivel", "Quantidade", { role: "style" } ],
                        <?php
                            for($i=0; $i < count($tecnicos); $i++){
                                if($username == $tecnicos[$i]['username'])
                                {
                                    $qtde1 = intVal($tecnicos[$i]["total_n1"]);
                                    $qtde2 = intVal($tecnicos[$i]["total_n2"]);
                                    $qtde3 = intVal($tecnicos[$i]["total_n3"]);
                                    $rand = str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
                                    $r1 = '#'.$rand;
                                    $rand = str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
                                    $r2 = '#'.$rand;
                                    $rand = str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
                                    $r3 = '#'.$rand;
                                }
                        ?>
                        ["<?php echo $nivel1 ?>", <?php echo $qtde1 ?>, "<?php echo $r1 ?>"],
                        ["<?php echo $nivel2 ?>", <?php echo $qtde2 ?>, "<?php echo $r2 ?>"],
                        ["<?php echo $nivel3 ?>", <?php echo $qtde3 ?>, "<?php echo $r3 ?>"],
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
                        title: "Quantidade de O.S. fechada por nível de complexidade",
                        width: 350,
                        height: 500,
                        bar: {groupWidth: "95%"},
                        legend: { position: "none" },
                    };
                    var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
                    chart.draw(view, options);
                }
            </script>
        @endif
    @endif
@stop



