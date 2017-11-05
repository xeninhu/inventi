@extends('layouts.app')

@section('content')

<div class="ui basic segment">

    <div class="ui header">Solicitar movimentação</div>

    <form class="ui form error" method="POST" action="{{ route('move-requests.store') }}">
        {{ csrf_field() }}
        <div class="field">
            <label for="name">Escolha um ou mais de seus itens</label>
            <select name="itens" multiple="" class="ui fluid dropdown">
                @foreach($itens as $item)
                    <option value="{{$item->id}}">{{$item->patrimony_number}} - {{$item->item}}</option>
                @endforeach
            </select>
        </div>
        <div class="ui top attached tabular menu">
            <div class="active item" data-tab="to-user">Mover para usuário</div>
            <div class="item" data-tab="to-coord">Mover para coordenação</div>
        </div>
        <div class="ui bottom attached active tab segment" data-tab="to-user">
            <p>teste user</p>
            <p></p>
        </div>
        <div class="ui bottom attached tab segment" data-tab="to-coord">
            <p>teste coord</p>
            <p></p>
        </div>
        <button type="submit" class="ui primary button">
            Criar colaborador
        </button>
    </form>

</div>
@endsection

@section('scripts')
    $('.ui.dropdown').dropdown();
    $('.tabular.menu .item').tab();
@endsection