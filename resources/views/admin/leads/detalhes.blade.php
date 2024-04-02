@extends('adminlte::page')

@section('title', 'Detalhes dos leads')

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
                            Utilize para vizualizar os leads de determinada classificação.
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
                        Em construção...
                    </div>
                </div>
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




