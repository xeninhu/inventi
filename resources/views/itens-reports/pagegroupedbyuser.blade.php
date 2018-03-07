@extends('layouts.app')

@section('content')
<div class="ui error message hidden">
    <i class="close icon"></i>
    <div class="header">
        Erro!
    </div>
    <p>Necessário escolher uma coordenação</p>
</div>
<div class="ui basic segment">

    <div class="ui header">Itens agrupados por funcionários</div>

    <form class="ui form error" method="get" action="{{route('itens-report.pagegroupeduser')}}" id="formSend">
        

        <div class="field">
            <label for="coordination" class="col-md-4 control-label">Coordenação</label>
            <select name="coordination" id="coordination">
                <option value="-1">Selecione uma coordenação</option>
            @foreach ($coordinations as $coord)
                <option value="{{ $coord->id }}" @if($coord->id == $coordination) selected @endif>{{$coord->name}}</option>
            @endforeach
            </select>
        </div>

        
        <button type="submit" class="ui primary button">
            Buscar
        </button>
    </form>

</div>

<h2 class="ui dividing header">Itens por usuários<a class="anchor" id="content"></a></h2>
@foreach ($users as $user)
    <div class="ui unstackable items">
        <div class="item">
            <div class="image">
                <img src="/images/persona.jpg">
            </div>
            <div class="content">
                <a class="header">{{$user->name}}</a>
                <div class="meta">
                    <span>{{$user->email}}</span>
                </div>
                <div class="description">
                    <p></p>
                </div>
                <div class="extra">
                    <div class="ui list">
                        @foreach($user->itens as $item)
                        <div class="item">
                            <i class="laptop icon"></i>
                            <div class="content">
                                {{$item->patrimony_number}} - {{$item->item}}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

<h2 class="ui dividing header">Itens sem dono</h2>

@endsection

@section('scripts')
$("#formSend").submit( function(event) {
    if($("#coordination").val()==-1) {
        $(".error").removeClass("hidden");
        event.preventDefault();
    }
}

)
@endsection