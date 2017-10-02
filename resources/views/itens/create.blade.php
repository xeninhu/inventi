@extends('layouts.app')

@section('content')
<div class="ui basic segment">

    <div class="ui header">Criar Item</div>

    <form class="ui form error" method="POST" action="{{-- route('createitem') --}}">
        {{ csrf_field() }}

        <div class="field">
            <label for="item" class="col-md-4 control-label">Item</label>
            <input id="item" type="text" class="form-control" name="item" value="{{ old('item') }}" required autofocus>
        </div>
        @if ($errors->has('item'))
            <div class="ui error message">
                <strong>{{ $errors->first('item') }}</strong>
            </div>
        @endif
        <div class="field">
            <label for="patrimony_number" class="col-md-4 control-label">Número de patrimônio</label>
            <input id="patrimony_number" type="number" class="form-control" name="patrimony_number" value="{{ old('patrimony_number') }}" required>
            @if ($errors->has('patrimony_number'))
                <span class="help-block">
                    <strong>{{ $errors->first('patrimony_number') }}</strong>
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
            Criar Item
        </button>
    </form>

</div>
@endsection
