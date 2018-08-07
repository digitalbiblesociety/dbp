<section class="hero is-primary">
    <div class="hero-head">
        @if(isset($breadcrumbs))
        <nav class="breadcrumb" aria-label="breadcrumbs">
            <ul>
                @foreach($breadcrumbs as $url => $breadcrumb)
                    @if($url != "#")
                    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <a itemprop="item" href="{{ $url }}">
                            <span itemprop="name">{{ $breadcrumb }}</span>
                            <meta itemprop="position" content="{{ $loop->iteration }}" />
                        </a>
                    </li>
                    @else
                        <li class="is-active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                            <span itemprop="item" aria-current="page">
                                <span itemprop="name">{{ $breadcrumb }}</span>
                                <meta itemprop="position" content="{{ $loop->iteration }}" />
                            </span>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
        @endif
    </div>
    <div class="hero-body">
        <div class="container">
            <h1 class="title">{{ $title }}</h1>
            @if(isset($subtitle)) <h2 class="subtitle">{{ $subtitle }}</h2> @endif
        </div>
    </div>
</section>