@extends('layouts.app')

@section('content')
<div class="ui basic segment">
    @if(Session::has('from-remove'))
        @component('components.success')
            @slot('message')
                Colaborador removido com sucesso.
            @endslot
        @endcomponent
    @endif
    
    @if(Session::has('error'))
        <div class="ui error message">
            <i class="close icon"></i>
            <div class="header">
                Erro!
            </div>
            <p>{{Session::get('error')}}</p>
        </div>
    @endif
    <div class="ui grid">
        <div class="left floated left aligned six wide column">
            <div class="ui header">
                Usuários
            </div>
        </div>
        <div class="right floated right aligned six wide column">
            <div class="ui label">
                <a href="{{route('register')}}">
                    <i class="add icon label"></i>Adicionar usuário
                </a>
            </div>
        </div>
    </div>
    <table class="ui celled table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>
                        <a href="{{ route('pagedituser',$user->id) }}">
                            <i class="edit icon"></i>
                        </a>
                        <a style="cursor:pointer" onclick="link_form_delete('{{ route('deleteuser',$user->id)}}');">
                            <i class="remove icon"></i>
                        </a>
                    </td>
                       
                </tr>
            @endforeach
            
           
        </tbody>
        <tfoot>
            <tr><th colspan="3">
            {{ $users->links() }}
            </th>
        </tr></tfoot>
        </table>
                
</div>
@component('components.alert')
    @slot('title')
        Remover colaborador
    @endslot
    @slot('content')
        Deseja realmente remover o colaborador selecionado?
    @endslot
    @slot('messageButton')
        Remover
    @endslot
@endcomponent
@endsection
