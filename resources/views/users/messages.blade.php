@extends('layouts.app')

@section('content')
<div class="ui basic segment">
    @if(Session::has('success'))
        <div class="ui success message">
            <i class="close icon"></i>
            <div class="header">
                Sucesso!
            </div>
            <p>Os e-mails foram colocados para processamento e estão sendo enviados.</p>
        </div>
    @endif

    <div class="ui header">Enviar Mensagem com itens a colaboradores</div>

    <form class="ui form error" id="form_messages" method="POST" action="{{route('send-itens-message')}}">
        {{ csrf_field() }}

        <div class="field">
            <label for="name">Escolha as coordenações a serem avisadas</label>
            <select name="coordinations[]" multiple="" class="ui fluid dropdown">
                @foreach($coordinations as $coordination)
                    <option value="{{$coordination->id}}" @if(old('coordination') && in_array($coordination->id,old('coordination'))) selected @endif>{{$coordination->name}}</option>
                @endforeach
            </select>
        </div>
        
        <button type="button" class="ui primary button" onclick="send_messages();">
            Enviar mensagens
        </button>
    </form>
</div>
@endsection

@section('components')
    @component('components.alert')
        @slot('title')
            Enviar mensagens
        @endslot
        @slot('content')
            Ao confirmar, todos os colaboradores das coordenações selecionadas receberão um e-mail informando os
            itens atribuídos a eles.<br>
            Deseja realmente continuar?
        @endslot
        @slot('messageButton')
            Enviar mensagens
        @endslot
    @endcomponent
@endsection

@section('scripts')
    function send_messages() {
        $('.ui.mini.modal')
            .modal({
                onDeny : function() {
                    $('#form_messages').submit();
                }
            })
            .modal('show');
    }
    
    $('.ui.dropdown').dropdown();
@endsection