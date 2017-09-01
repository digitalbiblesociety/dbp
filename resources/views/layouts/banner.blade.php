<div itemscope itemtype="http://schema.org/BreadcrumbList" @if(isset($noGradient)) class="nogradient" @endif>
    @foreach($breadcrumbs as $url => $breadcrumb)
        @if($url != "#")
            <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemprop="item" href="{{ $url }}">
                    <span itemprop="name">{{ $breadcrumb }}</span>
                    <meta itemprop="position" content="{{ $loop->iteration }}" />
                </a>
            </span>
        @else
            <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <span itemprop="item">
                    <span itemprop="name">{{ $breadcrumb }}</span>
                    <meta itemprop="position" content="{{ $loop->iteration }}" />
                </span>
            </span>
        @endif
    @endforeach
</div>
<section role="banner" itemscope itemtype="http://schema.org/WPHeader"  @if(!isset($noGradient) and isset($backgroundImage)) class="darken" @endif>

    <div class="banner-image">
        @if(isset($backgroundImage) AND isset($blurryImage))
            <img class="@if(isset($backgroundImageNoRetina)) no-retina @endif" src="{{ $blurryImage }}" data-src="{{ $backgroundImage }}" />
        @elseif(isset($backgroundImage) AND !isset($blurryImage))
            <img class="@if(isset($backgroundImageNoRetina)) no-retina @endif" src="{{ $backgroundImage }}" />
        @endif
    </div>

    @if(isset($title))
        <div class="banner-heading">
            <h1 itemprop="headline">{{ $title ?? "" }}</h1>
            <h2 itemprop="description" @if(isset($subtitle_direction)) dir="{{ $subtitle_direction }}" @endif @if(isset($subtitle_class)) class="{{ $subtitle_class }}" @endif>{{ $subtitle ?? "" }}</h2>
        </div>
    @endif
    @if(isset($tabs))
        <nav role="tablist" class="small-12 medium-10 large-8 centered">
            @foreach($tabs as $key => $value)
                @if(substr($key,0,1) == "/")
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
    @if(isset($map))<div id="map"></div> @endif
</section>