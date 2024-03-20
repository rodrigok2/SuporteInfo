@extends('adminlte::page')

@section('title', 'Detalhes dos leads')

@section('content_header')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@stop

@section('content')
    <section class="section">
        <div class="row">
            <br><br>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th scope="row">Grupo</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th scope="row">Subgrupo</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th scope="row">Servico</th>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-bordered table-striped table-responsive-stack"  id="tableOne" style="width:100%">
                <thead class="thead-dark">
                    <tr>
                        <th style="width:10%">O.S.</th>
                        <th style="width:25%">Classificacao</th>
                        <th style="width:40%">Descricao</th>
                        <th style="width:10%">Técnico</th>
                        <th style="width:15%"></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </section>
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




