@extends('layouts.app')

@section('content')
<div class="ui basic segment">

    <div class="ui header">Criar colaborador</div>

    <form class="ui form error" method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}

        <div class="field">
            <label for="name" class="col-md-4 control-label">Nome</label>
            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
            
        </div>
        @if ($errors->has('name'))
            <div class="ui error message">
                <strong>{{ $errors->first('name') }}</strong>
            </div>
        @endif
        <div class="field">
            <label for="email" class="col-md-4 control-label">E-mail</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>

        <div class="field">
            <label for="coordination" class="col-md-4 control-label">Coordenação</label>
            <select name="coordination" id="coordination">
            @foreach ($coordinations as $coordination)
                <option value="{{ $coordination->id }}">{{$coordination->name}}</option>
            @endforeach
            </select>

            @if ($errors->has('coordination'))
                <span class="help-block">
                    <strong>{{ $errors->first('coordination') }}</strong>
                </span>
            @endif
        </div>
        <button type="submit" class="ui primary button">
            Criar colaborador
        </button>
    </form>

</div>
@endsection
