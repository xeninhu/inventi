@extends('layouts.app')

@section('content')
<div class="ui basic segment">
    @if(Session::has('from-remove'))
        <div class="ui success message">
            <i class="close icon"></i>
            <div class="header">
                Sucesso!
            </div>
            <p>Coordenação removida com sucesso.</p>
        </div>
    @endif
    
    <div class="ui grid">
        <div class="left floated left aligned six wide column">
            <div class="ui header">
                Itens
            </div>
        </div>
        <div class="right floated right aligned six wide column">
            <div class="ui label">
                <a href="{{route('coordinations.create')}}">
                    <i class="add icon label"></i>Adicionar coordenação
                </a>
            </div>
        </div>
    </div>
        <table class="ui celled table">
        <thead>
            <tr>
                <th>Coordenação</th>
                <th>Coordenador</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($coordinations as $coord)
                <tr>
                    <td>{{$coord->name}}</td>
                    <td>{{$coord->coordinator!=null?$coord->coordinator->name:"Sem coordenador"}}</td>
                    <td>
                        <a href="{{ route('coordinations.edit',$coord->id) }}">
                            <i class="edit icon"></i>
                        </a>
                        <a style="cursor:pointer" onclick="link_form_delete('{{ route('coordinations.destroy',$coord->id)}}');">
                            <i class="remove icon"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            
           
        </tbody>
        <tfoot>
            <tr><th colspan="4">
            {{ $coordinations->links() }}
            </th>
        </tr></tfoot>
        </table>
                
</div>
@component('components.alert')
    @slot('title')
        Remover coordenação
    @endslot
    @slot('content')
        Deseja realmente remover a coordenação selecionada?
    @endslot
    @slot('messageButton')
        Remover
    @endslot
@endcomponent
@endsection