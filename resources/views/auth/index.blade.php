@extends('layouts.app')

@section('content')
<div class="ui main text container">
    @if(Session::has('from-remove'))
        <div class="ui success message">
            <i class="close icon"></i>
            <div class="header">
                Sucesso!
            </div>
            <p>Colaborador removido com sucesso.</p>
        </div>
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
    <div class="ui header">Usuários</div>

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
