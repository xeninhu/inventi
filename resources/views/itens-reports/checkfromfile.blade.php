@extends('layouts.app')

@section('content')
    <div class="ui basic segment">

        <div class="ui header">Comparar itens a partir de arquivo</div>
        <form class="ui form error" method="post" action="{{route('itens-report.checkfromfile')}}" enctype="multipart/form-data" id="formSend">
            {{ csrf_field() }}
            <div class="ui middle aligned center aligned grid container">
                <div class="ui fluid segment">
                    <input type="file" (change)="fileEvent($event)" name="itens" class="inputfile" id="embedpollfileinput" />
                    
                    <label for="embedpollfileinput" class="ui huge green right floated button">
                        <i class="ui upload icon"></i> 
                        Upload image
                    </label>
                    
                </div>
            </div>
            <br><br>
            <button type="submit" class="ui primary button">
                Gerar relatório
            </button>
           
        </form>

        @foreach($coord_with_itens as $coord)
            Coordenação: {{$coord->name}}
            @foreach($coord->itens as $item)
                {{$item->item}}
                <!--
                Uso PHP pois como já tenho que percorrer na view, removo os itens aqui. 
                Se fizesse no controller antes de enviar ia percorrer o laço duas vezes.
                -->
                @php
                    $to_remove[] = $item->patrimony_number;
                @endphp
                <br>
            @endforeach
            <br><br><br>
        @endforeach

        @php
            $itens = array_diff($itens,$to_remove);
        @endphp
        
        Itens não encontrados
        @foreach($itens as $item)
            {{$item}}
            <br>
        @endforeach

    </div>

@endsection

@section('styles')
.grid.container {
  margin-top: 5em;
}
.inputfile {
	width: 0.1px;
	height: 0.1px;
	opacity: 0;
	overflow: hidden;
	position: absolute;
	z-index: -1;
}
@endsection