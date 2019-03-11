@extends('layouts.app')

@section('content')
<div class="ui basic segment">

    <div class="ui header">Criar Coordenação</div>

    <form class="ui form error" method="POST" action="{{route('coordinations.store')}}">
        {{ csrf_field() }}

        <div class="field">
            <label for="name" class="col-md-4 control-label">Coordenação</label>
            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>
        </div>
        @if ($errors->has('name'))
            <div class="ui error message">
                <strong>{{ $errors->first('name') }}</strong>
            </div>
        @endif

        <div class="field">
            <label for="coordinator" class="col-md-4 control-label">Coordenador</label>
            <select name="coordinator_id" id="coordinator_id">
                <option value=0>Sem coordenador</option>
            @foreach ($users as $user)
                @if(!$user->coordinator)
                    <option value="{{ $user->id }}">{{$user->name}}</option>
                @endif
            @endforeach
            </select>

            @if ($errors->has('coordinator_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('coordinator_id') }}</strong>
                </span>
            @endif
        </div>
        
        <button type="submit" class="ui primary button">
            Criar Coordenação
        </button>
    </form>

</div>
@endsection
