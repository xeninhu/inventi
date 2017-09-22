@extends('layouts.app')

@section('content')
<div class="ui main text container">
    
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
                        </a><i class="remove icon"></i></td>
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
@endsection
