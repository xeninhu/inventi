@extends('layouts.app')

@section('content')
<div class="ui basic segment">

    @if(Session::has('correct_itens'))
        <div class="ui success message">
            <i class="close icon"></i>
            <div class="header">
                Sucesso!
            </div>
            <p>Os seguintes itens foram movidos para o usuário {{(Session::get('user'))->name}}</p>
            <ul>
                @foreach(Session::get('correct_itens') as $item)
                    <li>{{$item->patrimony_number}} - {{$item->item}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(Session::has('wrong_ids'))
        <div class="ui error message">
            <i class="close icon"></i>
            <div class="header">
                Erro!
            </div>
            <p>Alguns itens não foram encontrados na base e não puderam ser movidos</p>
        </div>
    @endif

    <div class="ui header">Movimentar Itens para colaboradores</div>

    <form class="ui form error" method="POST" action="{{route('itens.move')}}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <div class="ui field">
            <label for="user">Colaborador</label>
            <div class="ui search selection dropdown" id="dropdown_users">
                <input type="hidden" name="user">
                <i class="dropdown icon"></i>
                <input type"text" class="search">
                <div class="default text">
                    Buscar um usuário
                </div>
            </div>
        </div>

        <div class="ui field">
            <label for="type">Itens</label>
            <div class="ui fluid multiple search selection dropdown" id="dropdown_itens">
                <input type="hidden" name="itens">
                <i class="search icon"></i>
                <div class="default text">Digite mais de 4 dígitos para busca...</div>
            </div>
        </div>
        
        <button type="submit" class="ui primary button">
            Movimentar itens
        </button>
    </form>

</div>
@endsection

@section('scripts')
$('#dropdown_itens').dropdown({
   fields: {name: 'name', value: 'value'},
   minCharacters:4,
   apiSettings: {
      url: '/itens/search/{query}'
   }
});
$('#dropdown_users').dropdown({
   fields: {name: 'name', value: 'value'},
   minCharacters:4,
   apiSettings: {
      url: '/users/search/{query}'
   }
});
@endsection