@extends('adminlte::page')

@section('title', 'Andamentos por serviço')

@section('content_header')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0">
                    <div class="row">
                        <div style="padding-left: 10px;">
                            Lista de andamentos por serviço
                        </div>
                        <div style="font-size: 14px; vertical-align: middle; padding-left 10px; padding-top: 10px;">
                            <i class="fa fa-info-circle"></i>
                                Utilize para visualizar todos os andamentos lançados no SUL para um determinado grupo de serviço.
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
                <div class="card" style="overflow-x: auto;">
                    <div class="card-body">
                        <div class="col-sm-12">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th scope="row" style="width: 10%">Servico</th>
                                        <td style="width: 40%">{{ $descricao_servico }}</td>
                                        <th scope="row" style="width: 10%">Subgrupo</th>
                                        <td style="width: 40%">{{ $descricao_subgrupo }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th scope="row" style="width: 10%">Grupo</th>
                                        <td style="width: 90%">{{ $descricao_grupo }}</td>
                                    </tr>
                                </tbody>
                            </table>
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
                                <th style="width:20%">Classificacao</th>
                                <th>Descricao</th>
                                <th >Técnico</th>
                                <th style="width:10%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i = 0; $i < count($lista_de_andamentos); $i++)
                                <tr>
                                    <td>{{ $lista_de_andamentos[$i]['os_id'] }}</td>
                                    <td>{{ $lista_de_andamentos[$i]['classificacao_os'] }}</td>
                                    <td>{{ $lista_de_andamentos[$i]['descricao'] }}</td>
                                    <td>{{ $lista_de_andamentos[$i]['username'] }}</td>
                                    <td>
                                        <form action="{{ route('admin.os.detalhes', ["os_id" => $lista_de_andamentos[$i]['os_id']]) }}" method="GET">
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
@stop



