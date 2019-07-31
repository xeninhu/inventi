@extends('layouts.app')

@section('content')
<div class="ui basic segment">
    @if(Session::has('successMessage'))
        @component('components.success')
            @slot('message')
                {{Session::get('successMessage')}}
            @endslot
        @endcomponent
    @endif
    <div class="ui header">Alterar dados do colaborador</div>

    <form class="ui form error" method="POST" action="{{ route('edituser') }}">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="put" />
                        
        <input type="hidden" name="id" value="{{$user->id}}" />

        <div class="field">
            <div class="ui toggle checkbox">
                <input type="checkbox" name="admin" value='1' {{$errors->any()?((old('admin')!==null)?'checked':'') : $user->admin?'checked':'' }} >
                <label><b>Usuário Administrador</b></label>
            </div>
        </div>

        <div class="field">
            <label for="name" class="col-md-4 control-label">Nome</label>
            
            <input id="name" type="text" class="form-control" name="name" value="{{$errors->any()? old('name'):$user->name }}" required autofocus>
        </div>
        @if ($errors->has('name'))
            <div class="ui error message">
                <strong>{{ $errors->first('name') }}</strong>
            </div>
        @endif

        <div class="field">
            <label for="email" class="col-md-4 control-label">E-mail</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ $errors->any()? old('email'):$user->email }}" disabled>
        </div>
        @if ($errors->has('email'))
            <div class="ui error message">
                <strong>{{ $errors->first('email') }}</strong>
            </div>
        @endif

        <div class="field">
            <label for="coordination" class="col-md-4 control-label">Coordenação</label>
            <select name="coordination" id="coordination">
            @foreach ($coordinations as $coordination)
                <option value="{{ $coordination->id }}" 
                @if ( ($coordination->id===$user->coordination->id && !$errors->any())
                      || ($coordination->id==old('coordination') && $errors->any())
                    ) selected @endif >
                    {{$coordination->name}} </option>
            @endforeach
            </select>
        </div>
        @if ($errors->has('coordination'))
            <div class="ui error message">
                <strong>{{ $errors->first('coordination') }}</strong>
            </div>
        @endif
        <button type="submit" class="ui primary button">
            Alterar dados
        </button>
    </form>

</div>
@endsection
