@extends('layouts.app')

@section('content')
<div class="ui basic segment">

    <div class="ui header">Itens agrupados por funcionários</div>

    <form class="ui form error" method="POST" action="{{route('itens.move')}}" id="formSend">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <div class="field">
            <label for="coordination" class="col-md-4 control-label">Coordenação</label>
            <select name="coordination" id="coordination">
                <option value="-1">Selecione uma coordenação</option>
            @foreach ($coordinations as $coordination)
                <option value="{{ $coordination->id }}">{{$coordination->name}}</option>
            @endforeach
            </select>
        </div>

        
        <button type="submit" class="ui primary button">
            Buscar
        </button>
    </form>

</div>
@endsection

@section('scripts')
$("#formSend").submit( function(event) {
    if($("#coordination").val()==-1) {
        alert("passou");
        event.preventDefault();
    }
}

)
@endsection