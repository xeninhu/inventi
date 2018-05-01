@extends('layouts.app')

@section('content')
    <div class="ui basic segment">

        <div class="ui header">Comparar itens a partir de arquivo</div>
        <form class="ui form error" method="post" action="{{route('itens-report.checkfromfile')}}" enctype="multipart/form-data" id="formSend">
            {{ csrf_field() }}
            <div class="ui middle aligned center aligned grid container">
                <div class="ui fluid segment">
                    <input type="file" onchange="$('#formSend').submit()" name="itens" class="inputfile" id="embedpollfileinput" />
                    
                    <label for="embedpollfileinput" class="ui huge green right floated button">
                        <i class="ui upload icon"></i> 
                        Carregar arquivo
                    </label>                    
                </div>
            </div>
            <br><br>
           
           
        </form>

        <div class="ui cards">
            @foreach($coord_with_itens as $coord)
                @if($coord->itens_count>0)
                    <div class="card">
                        <div class="content">
                            <div class="header">Coordenação: {{$coord->name}}</div>
                            <div class="ui celled list">
                            @foreach($coord->itens as $item)
                                <div class="item">
                                    <i class="ui computer icon"></i>
                                    <div class="content">
                                        <div class="header">{{$item->patrimony_number}}</div>
                                        {{$item->item}}
                                        </div>
                                </div>
                                <!--
                                Uso PHP pois como já tenho que percorrer na view, removo os itens aqui. 
                                Se fizesse no controller antes de enviar ia percorrer o laço duas vezes.
                                -->
                                @php
                                    $to_remove[] = $item->patrimony_number;
                                @endphp
                            @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
            @php
                if(!empty($to_remove))
                    $itens = array_diff($itens,$to_remove);
            @endphp
            
            @if(!empty($itens))
                <div class="card">
                    <div class="content">
                        <div class="header">Itens não encontrados</div>
                        <div class="ui celled list">
                            @foreach($itens as $item)
                                <div class="item">
                                    <i class="ui computer icon"></i>
                                    <div class="content">
                                       {{$item}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            @endif
        </div>
        

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