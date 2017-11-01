<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Movimentação de item</div>

                    <div class="panel-body">

                        Prezado colaborador,<br><br>

                        Os seguintes itens foram atribuídos a você:<br>
                        <ul>
                            @foreach($itens as $item)
                                <li>{{$item->patrimony_number}} - {{$item->item}}</li>
                            @endforeach
                        </ul>
                        Caso o item não tenha sido endereçado para você, favor contactar o seu coordenador.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

