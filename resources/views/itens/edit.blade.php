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

    <div class="ui header">Editar Item</div>

    <form class="ui form error" method="POST" action="{{route('itens.update',$item->id)}}">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="put" />

        <div class="ui field search">
            <label for="type" class="col-md-4 control-label">Tipo do item</label>
            <div class="ui icon input">
                <input class="prompt" type="text" name="item_type" value="{{ $errors->any()? old('item_type'):$item->type->type }}" placeholder="Iremos buscar caso o tipo já exista..." required>
                <i class="search icon"></i>
            </div>
            <div class="results"></div>
        </div>

        <div class="field">
            <label for="item" class="col-md-4 control-label">Item</label>
            <input id="item" type="text" class="form-control" name="item" value="{{ $errors->any()? old('item'):$item->item }}" required autofocus>
        </div>
        @if ($errors->has('item'))
            <div class="ui error message">
                <strong>{{ $errors->first('item') }}</strong>
            </div>
        @endif
        <div class="field">
            <label for="patrimony_number" class="col-md-4 control-label">Número de patrimônio</label>
            <input id="patrimony_number" type="number" class="form-control" name="patrimony_number" value="{{ $errors->any()? old('patrimony_number'):$item->patrimony_number }}" required>
            @if ($errors->has('patrimony_number'))
                <span class="help-block">
                    <strong>{{ $errors->first('patrimony_number') }}</strong>
                </span>
            @endif
        </div>
        <div class="field">
            <label for="coordination" class="col-md-4 control-label">Coordenação</label>
            <select name="coordination" id="coordination">
            @foreach ($coordinations as $coordination)
                <option value="{{ $coordination->id }}"
                @if ( ($coordination->id===$item->coordination->id && !$errors->any())
                      || ($coordination->id==old('coordination') && $errors->any())
                    ) selected @endif >
                    {{$coordination->name}}</option>
            @endforeach
            </select>

            @if ($errors->has('coordination'))
                <span class="help-block">
                    <strong>{{ $errors->first('coordination') }}</strong>
                </span>
            @endif
        </div>
        <button type="submit" class="ui primary button">
            Editar Item
        </button>
    </form>

</div>
@endsection

@section('scripts')
$('.ui.search')
  .search({
    apiSettings: {
      url: '/item_types/{query}'
    },
    fields: {
      results : 'results',
      title   : 'title',
    },
    error: {
      noResults: 'Esse tipo será cadastrado'
    },
    minCharacters : 4
  });
@endsection