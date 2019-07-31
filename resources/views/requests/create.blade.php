@extends('layouts.app')

@section('content')

<div class="ui basic segment">
    @if(Session::has('success'))
        @component('components.success')
            @slot('message')
                Movimentações solicitadas com sucesso
            @endslot
        @endcomponent
    @endif

    @if($errors->has('item_exists'))
        <div class="ui error message">
            <i class="close icon"></i>
            <div class="header">
                Erro!
            </div>
            <p>{{$errors->first('item_exists')}}</p>
        </div>
    @endif

    @if($errors->has('forbidden_itens'))
        <div class="ui error message">
            <i class="close icon"></i>
            <div class="header">
                Erro!
            </div>
            <p>{{$errors->first('forbidden_itens')}}</p>
        </div>
    @endif

    <div class="ui header">Solicitar movimentação</div>

    <form class="ui form error" method="POST" action="{{ route('move-requests.store') }}">
        {{ csrf_field() }}
        
        <div class="field">
            <label for="name">Escolha um ou mais de seus itens</label>
            @if(!count($itens))
                 <select name="itens[]"  class="ui fluid dropdown" disabled>
                    <option value="">Usuário não possui itens</option> 
                 </select>
            @else
                <select name="itens[]" multiple="" class="ui fluid dropdown">
                    @foreach($itens as $item)
                        <option value="{{$item->id}}" @if(old('itens') && in_array($item->id,old('itens'))) selected @endif>{{$item->patrimony_number}} - {{$item->item}}</option>
                    @endforeach
                </select>
            @endif
        </div>
        @if ($errors->has('itens'))
            <div class="ui error message">
                <strong>{{ $errors->first('itens') }}</strong>
            </div>
        @endif
        <div class="field">
            <label for="description">Escreva o motivo da solicitação</label>
            <input type="text" name="description" value="{{old('description')}}"/>
        </div>
        @if ($errors->has('description'))
            <div class="ui error message">
                <strong>{{ $errors->first('description') }}</strong>
            </div>
        @endif
        <div class="ui top attached tabular menu">
            <a class="active item" data-tab="user-tab">Mover para usuário</a>
            <a class="item" data-tab="coord-tab">Mover para coordenação</a>
        </div>
        <div class="ui bottom attached active tab segment" data-tab="user-tab">
            <div class="field">
                <label for="name">Escolha um usuário de sua coordenação</label>
                <div class="ui fluid search selection dropdown">
                    <input type="hidden" name="user_to" id="user_to" value="{{old('user_to')}}">
                    <i class="dropdown icon"></i>
                    <div class="default text">Busque um usuário</div>
                    <div class="menu">
                        @foreach($users as $user)
                            <div class="item" data-value="{{$user->id}}">{{$user->name}}</div>
                        @endforeach
                    </div>
                </div>
            </div>
            @if ($errors->has('user'))
                <div class="ui error message">
                    <strong>{{ $errors->first('user') }}</strong>
                </div>
            @endif
        </div>
        <div class="ui bottom attached tab segment" data-tab="coord-tab">
            <div class="field">
                <div class="ui toggle checkbox">
                    <input type="checkbox" name="my_coord" value='1' id="my_coord" disabled>
                    <label><b>Devolver para minha coordenação</b></label>
                    <p></p>
                    <p></p>
                </div>
                
                <div id="div_other_coord">
                    <div class="field">
                        <label for="name">Escolha uma coordenação para enviar o(s) item(ns)</label>
                        <div class="ui fluid search selection dropdown">
                            <input type="hidden" name="other_coord" id="other_coord" disabled>
                            <i class="dropdown icon"></i>
                            <div class="default text">Busque uma coordenação</div>
                            <div class="menu">
                                @foreach($coordinations as $coordination)
                                    <div class="item" data-value="{{$coordination->id}}">{{$coordination->name}}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="ui primary button">
            Solicitar movimentação de itens
        </button>
    </form>

</div>
@endsection

@section('scripts')
    $('.ui.dropdown').dropdown();
    $('.tabular.menu .item').tab({
        'onVisible': function(path) { //Enviando apenas os inputs do tab visível e disabilitando o resto
            if(path=='user-tab') {
                $("#user").prop("disabled",false);
                $("#my_coord, #other_coord").prop("disabled",true);
            }
            else if(path=='coord-tab') {
                $("#user").prop("disabled",true);
                $("#my_coord, #other_coord").prop("disabled",false);
            }
        }
    });
    $('#my_coord').change(function(){
        if(this.checked)
            $('#div_other_coord').hide();
        else
            $('#div_other_coord').show();
    })
@endsection