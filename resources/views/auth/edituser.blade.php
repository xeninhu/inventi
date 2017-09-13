@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                @isset($successMessage)
                    <div class="alert alert-success">
                        <strong>Sucesso!</strong> Colaborador atualizado com sucesso.
                    </div>
                @endif
                <div class="panel-heading">Alterar dados do colaborador</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('user') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put" />
                        
                        <input type="hidden" name="id" value="{{$user->id}}" />
                        

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Nome</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" disabled>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('coordination') ? ' has-error' : '' }}">
                            <label for="coordination" class="col-md-4 control-label">Coordenação</label>

                            <div class="col-md-6">
                                <select name="coordination" id="coordination">
                                @foreach ($coordinations as $coordination)
                                    <option value="{{ $coordination->id }}" 
                                    @if ($coordination->id===$user->coordination->id) selected @endif >
                                        {{$coordination->name}} </option>
                                @endforeach
                                </select>

                                @if ($errors->has('coordination'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('coordination') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Alterar dados
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
