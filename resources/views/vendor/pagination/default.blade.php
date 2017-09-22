@if ($paginator->hasPages())
    <div class="ui right floated pagination menu">
        @if ($paginator->onFirstPage())
            <a rel="prev" class="icon item disabled">
                <i class="left chevron icon"></i>
            </a>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="icon item">
                <i class="left chevron icon"></i>
            </a>
        @endif

       @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <a class="item">{{ $element }}</a>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a class="item disabled">{{ $page }}</a>
                    @else
                        <a href="{{ $url }}" class="item">{{ $page }}</a>
                        
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="icon item">
                <i class="right chevron icon"></i>
            </a>
        @else
            <a rel="next" class="icon item disabled">
                <i class="right chevron icon"></i>
            </a>
        @endif
      </div>
@else
<div class="ui right floated pagination menu">
        <a rel="prev" class="icon item disabled">
            <i class="left chevron icon"></i>
        </a>
        <a class="item disabled">1</a>
        <a rel="next" class="icon item disabled">
            <i class="right chevron icon"></i>
        </a>
      </div>
@endif
