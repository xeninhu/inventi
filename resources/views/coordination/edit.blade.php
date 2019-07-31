@extends('layouts.app')

@section('content')
<div class="ui basic segment">
    @if(Session::has('successMessage'))
        <div class="ui success message">
            <i class="close icon"></i>
            <div class="header">
                Sucesso!
            </div>
            <p>{{Session::get('successMessage')}}</p>
        </div>
    @endif

    <div class="ui header">Editar Coordenação</div>

    <form class="ui form error" method="POST" action="{{route('coordinations.update',$coord->id)}}">
        {{ csrf_field() }}
        
        <input type="hidden" name="_method" value="put" />
        <input type="hidden" name="id" value="{{$coord->id}}"/>

        <div class="field">
            <label for="name" class="col-md-4 control-label">Coordenação</label>
            <input id="name" type="text" class="form-control" name="name" value="{{$coord->name}}" required>
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
                @if(!$user->coordinator || $user->coordinator->id==$coord->id)
                    <option value="{{ $user->id }}" @if($user->coordinator && $user->coordinator->id===$coord->id) selected @endif>
                        {{$user->name}}
                    </option>
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
            Editar Coordenação
        </button>
    </form>

</div>
@endsection
