<section class="hero is-primary is-{{ $size ?? 'small' }} is-bold">
    <div class="hero-body">
        <div class="container">
            <article class="media">
                @isset($image)
                <figure class="media-left">
                    <div class="image"><img src="{{ $image }}"></div>
                </figure>
                @endisset
                <div class="media-content">
                    <div class="content">
                        @isset($title) <h1 class="title">{{ $title }}</h1> @endisset
                        @isset($subtitle) <h2 class="subtitle">{!! $subtitle !!}</h2> @endisset
                        @isset($actions)
                            <nav class="field is-grouped columns is-mobile is-centered">
                                @foreach($actions as $url => $action)
                                    <div class="control"><a class="button is-dark" href="{{ $url }}">{{ $action }}</a></div>
                                @endforeach
                            </nav>
                        @endisset
                    </div>
                    @isset($breadcrumbs)
                        <nav class="breadcrumb has-dot-separator" aria-label="breadcrumbs">
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
                                            <a itemprop="item" aria-current="page" href="#">
                                                <span itemprop="name">{{ $breadcrumb }}</span>
                                                <meta itemprop="position" content="{{ $loop->iteration }}" />
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </nav>
                    @endisset
                </div>
                <div class="media-right">

                </div>
            </article>

        </div>
    </div>
</section>