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
        
        <button type="submit" class="ui primary button">
            Criar Coordenação
        </button>
    </form>

</div>
@endsection
