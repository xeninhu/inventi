@extends('layouts.app')

@section('content')
<div class="ui basic segment">
    @if(Session::has('from-remove'))
        <div class="ui success message">
            <i class="close icon"></i>
            <div class="header">
                Sucesso!
            </div>
            <p>Item removido com sucesso.</p>
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
                <a href="{{route('itens.create')}}">
                    <i class="add icon label"></i>Adicionar item
                </a>
            </div>
            <div class="ui label">
                <a href="{{route('itens.movepage')}}">
                    <i class="shipping icon label"></i>Movimentar itens
                </a>
            </div>
        </div>
    </div>
    <form class="ui form" method="get" action="{{route('itens.index')}}">
        <div class="ui action input">
            <input type="text" placeholder="Numero de patrimônio" name="patrimony_number">
            <button class="ui icon button">
                <i class="search icon"></i>
            </button>
        </div>
    </form>
    <table class="ui celled table">
        <thead>
            <tr>
                <th>Nº de patrimônio</th>
                <th>Item</th>
                <th>Coordenação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($itens as $item)
                <tr>
                    <td>{{$item->patrimony_number}}</td>
                    <td>{{$item->item}}</td>
                    <td>{{$item->coordination->name}}</td>
                    <td>
                        <a href="{{ route('itens.edit',$item->id) }}">
                            <i class="edit icon"></i>
                        </a>
                        <a style="cursor:pointer" onclick="link_form_delete('{{ route('itens.destroy',$item->id)}}');">
                            <i class="remove icon"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            
           
        </tbody>
        <tfoot>
            <tr><th colspan="4">
            {{ $itens->links() }}
            </th>
        </tr></tfoot>
        </table>
                
</div>
@component('components.alert')
    @slot('title')
        Remover item
    @endslot
    @slot('content')
        Deseja realmente remover o item selecionado?
    @endslot
    @slot('messageButton')
        Remover
    @endslot
@endcomponent
@endsection