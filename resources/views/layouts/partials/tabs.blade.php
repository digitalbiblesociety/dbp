@if(isset($tabs))
    <nav role="tablist" class="small-12 medium-10 large-8 centered">
        @foreach($tabs as $key => $value)
            @if(substr($key,0,1) == "/" | substr($key,0,4) == "http")
                <a class="external" href="{{ $key }}">{{ $value }}</a>
            @elseif($value != "")
                <a role="tab"
                   @if(!key_exists("#",$tabs))
                   @if($loop->first)
                   aria-selected="true"
                   @else
                   aria-selected="false"
                   @endif
                   @else
                   @if($key == "#")
                   aria-selected="true"
                   @else
                   aria-selected="false"
                   @endif
                   @endif
                   controls="{{ $key }}" href="#{{ $key }}">{{ $value }}</a>
            @endif
        @endforeach
    </nav>
@endif