@extends('layouts.app')

@section('content')
<div class="ui unstackable items">
    <div class="item">
        <div class="image">
            <img src="/images/persona.jpg">
        </div>
        <div class="content">
            <a class="header">{{$user->name}}</a>
            <div class="meta">
                <span>{{$user->email}}</span>
            </div>
            <div class="description">
                <p></p>
            </div>
            <div class="extra">
                <div class="ui list">
                    @foreach($user->itens as $item)
                    <div class="item">
                        <i class="laptop icon"></i>
                        <div class="content">
                        <a href="#"> {{$item->patrimony_number}} -   
                            @if ($item->coordination->id!=$user->coordination->id)
                            <div class="ui red label"><i class="ui exclamation triangle icon"></i>Item de outra coordenação: {{$item->coordination->name}}</div>
                            @endif
                            {{$item->item}}
                        </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
