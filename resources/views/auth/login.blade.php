@extends('layouts.app')

@section('content')
<div class="ui main text container">
    
    <div class="ui header">Login</div>

    <form class="ui form" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}

        <!--div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}"-->
        <div class="field">
            <label for="email" class="">E-Mail Address</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>

        <!--div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}"-->
        <div class="field">
            <label for="password" class="">Password</label>
            <input id="password" type="password" class="form-control" name="password" required>

            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>

        <div class="field">
            <div class="ui checkbox">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label> Lembre-se de mim </label>
            </div>
        </div>
        <button type="submit" class="ui button">
            Login
        </button>

        <a class="btn btn-link" href="{{ route('password.request') }}">
            Esqueceu sua senha?
        </a>
    </form>
                
</div>
@endsection
