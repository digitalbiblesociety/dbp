<!DOCTYPE html>
<html class="no-js" lang="en" xmlns:fb="http://ogp.me/ns/fb#">
<head>
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, minimum-scale=1, maximum-scale=1">
    <meta charset="utf-8">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-title" content="Bible.is" />
    <meta name="author" content="Faith Comes By Hearing"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    @if(env('FACEBOOK_APP_ID'))
    <meta property="og:type" content="book" />
    <meta property="og:title" content="{{ $verses->first()->book->currentTranslation->name ?? $verses->first()->book_id }} {{ $verses->first()->chapter_number }}| Bible.is" />
    <meta property="og:description" content="{{ $verses->first()->verse_text }}..." />
    <meta property="og:url" content="/{{ $verses->first()->bible_id }}/{{ $verses->first()->book_id }}/{{ $verses->first()->chapter_number }}" />
    <meta property="og:image" content="/images/FB-post-icon.png?cr=1" />
    <meta property="og:site_name" content="Bible.is" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_APP_ID') }}" />
    @endif
    @if(env('TWITTER_APP_ID'))
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:site" content="@bibleis"/>
    <meta name="twitter:creator" content="@audiobible"/>
    <meta name="twitter:title" content="{{ $verses->first()->book->currentTranslation->name ?? $verses->first()->book_id }} {{ $verses->first()->chapter_number }}"/>
    <meta name="twitter:description" content="{{ $verses->first()->verse_text }}..."/>
    <meta name="twitter:image" content="http://listen.bible.is/images/Twitter-card-icon.png?cr=1"/>
    <meta name="twitter:app:id:iphone" content="{{ env('TWITTER_APP_ID') }}"/>
    <meta name="twitter:app:id:ipad" content="{{ env('TWITTER_APP_ID') }}"/>
    <meta name="twitter:app:id:googleplay" content="com.faithcomesbyhearing.android.bibleis"/>
    @endif
    @if(env('ITUNES_APP_ID'))
    <meta name="apple-itunes-app" content="app-id={{ env("ITUNES_APP_ID") }}" />
    @endif
    <meta name="google-play-app" content="app-id=com.faithcomesbyhearing.android.bibleis">
    <link rel="favorite icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" href="/images/icons/apple-touch-icon-152.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/images/icons/apple-touch-icon-76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="t/images/icons/apple-touch-icon-120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/images/icons/apple-touch-icon-152.png">
    <link rel="apple-touch-startup-image" href="/images/spinner.gif" />
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />
    <!--[if IE 8]> <link href="/css/ie8.css?cr=1" rel="stylesheet" type="text/css" media="screen" /> -->
    <style>
        body {
            min-height:100vh;
        }

        .no-fouc {visibility: hidden!important;}

        nav {
            background:#A14;
            height:60px;
        }

        nav input,
        nav a {
            float:left;
        }

        .version-button,
        .font-button,
        .search-button,
        .chapter-button {
            float:left;
            height:40px;
            width:40px;
            display:block;
            background-color:#770011;
            color:#FFF;
            padding:10px;
            margin:10px 0;
            font-size:12px;
        }


        .font-button {
            float:right;
            margin-right:15px;
        }

        .font-button:hover {
            color:#FFF;
            font-weight:bold;
        }

        nav #search-form input {
            width:calc(100% - 120px);
            height:40px;
            margin:10px 0;
            border:none;
        }

        article {
            text-align:justify;
            line-height:1.5;
            font-size:1.5rem;
            padding-top:70px;
        }


        a                           {text-decoration:none!important;-webkit-transition:all 225ms ease;-moz-transition:all 225ms ease;transition:all 225ms ease}
        .panel                {background:#444;overflow:scroll}
        .panel ul               {list-style:none;padding:0;margin:0;text-align:center;}
        .panel > ul             {height:800px;overflow: scroll}
        .panel > ul li a        {display:block;width:100%;height:50px;line-height:50px;background:transparent;color:#fff}
        .panel > ul li a:hover  {background:#555}
        .panel input          {width:100%;
            height: 60px;
            padding: 10px;
            background: #f1f1f1;
            text-indent: 30px;
        }

        #settings-panel li,
        #settings-panel a {
            font-size:16px;
        }

        #settings-panel .list ul {
            border-top:thin solid #333;
        }

        #settings-panel .list > li {
            color:#FFF;
            padding:10px;
        }

        #navigation-panel .list .book {
            width: 100%;
            display: block;
            color: #FFF;
        }

        #navigation-panel .list .chapter {
            float: left;
            width: 20%;
            height: 50px;
            display: block;
            line-height: 50px;
            /* padding: 10px; */
            background: #FFF;
            color: #222;
            border: thin solid #222;
        }

        #navigation-panel .list .chapters {
            padding:10px;
        }

        .result {
            font-size: .75rem;
            background: #f1f1f1;
            padding:10px;
            margin:5px;
        }

        .result a {
            color:#222;
        }

        .result a b {
            color:#701;
        }

        .result a:hover {
            color:#000;
            background:#f2f2f2;
        }

        .sideNav {
            position: fixed;
            top:50%;
            padding:100px 10px;
            margin-top:-100px;
            border-radius: 5px;
        }

        .sideNav:hover {
            background: #ccc;
            color:#FFF;
        }

        .sideNav:hover .chevron:before {
            color:#FFF;
        }

        .sideNav.left {
            left:10px;
        }

        .sideNav.right {
            right:10px;
        }

        .chevron::before {
            border-style: solid;
            border-width: .25em .25em 0 0;
            content: '';
            display: inline-block;
            height: 0.9em;
            left: 0.3em;
            position: relative;
            top: 0.3em;
            transform: rotate(-45deg);
            vertical-align: top;
            width: 0.9em;
            color:#ccc;
        }

        .chevron.right:before {
            left: 0;
            transform: rotate(45deg);
        }

        .chevron.bottom:before {
            top: 0;
            transform: rotate(135deg);
        }

        .chevron.left:before {
            left: 0.25em;
            transform: rotate(-135deg);
        }

        .logo {
            width:100px;
            margin-top:15px;
        }


        .chapters {
            visibility: hidden;
            display: none;
        }

        .chapters.active {
            visibility: visible;
            display: block;
        }

    </style>
</head>
<body>
<nav>
    <div class="small-3 columns">
        <svg class="logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 242 60">
            <path fill="#FFF" d="M41.2 49c-1 2-2 3.7-3.7 5.2-1.6 1.4-3.5 2.5-5.7 3.2-2.2.8-4.6 1-7.3 1H0V0h23.5C29.3 0 33.8 1.5 37 4.4c3 3 4.6 7 4.6 12 0 1.7-.2 3.2-.7 4.5-.5 1.2-1 2.4-2 3.4-.6 1-1.4 2-2.3 2.6-.8.7-1.6 1.2-2.3 1.5 1 .5 1.8 1 2.7 1.7 1 .7 2 1.6 2.7 2.6.8 1 1.4 2.3 2 3.7.5 1.5.7 3.2.7 5.2 0 2.7-.4 5-1.2 7.2zm-13-37c-1.2-1.2-3-1.8-5.6-1.8H11.4v13.5h11.2c2.5 0 4.4-.6 5.7-2 1.2-1 2-2.7 2-4.8 0-2-.7-3.7-2-5zm1 24c-1.3-1.4-3.2-2-5.8-2h-12v14.3h12c2.6 0 4.5-.7 5.8-2C30.4 44.7 31 43 31 41s-.6-3.6-1.8-5zM49.8 58.5V16H60v42.5H49.8M103.8 42.7c0 1.7-.2 3.4-.5 5s-.7 3.2-1.3 4.6c-.7 1.5-1.5 2.7-2.5 3.8-1.3 1.3-2.8 2.3-4.7 3-2 .6-4 1-6 1-2.3 0-4.3-.3-6-1-1.7-.7-3.3-2-4.8-3.6v4H67.3V0h11v19.8c1.5-1.6 3-2.7 4.7-3.4 1.7-.6 3.6-1 6-1 2 0 4 .4 5.8 1 2 .8 3.5 1.8 4.7 3 1 1 2 2.4 2.5 3.8.6 1.4 1 3 1.3 4.5.3 1.7.5 3.3.5 5v10zm-11.2-10c0-1.4-.5-2.7-1-4-.5-1-1.2-1.8-2.2-2.5-1-.6-2.2-1-3.8-1-1.6 0-3 .4-4 1-1 .7-1.6 1.5-2 2.6-.6 1.2-1 2.5-1 4-.2 1.5-.3 3-.3 5 0 1.7 0 3.3.2 5 .2 1.4.5 2.7 1 3.8.5 1 1.2 2 2.2 2.7 1 .6 2.3 1 4 1 1.5 0 2.8-.4 3.7-1 1-.7 1.7-1.6 2.2-2.7.5-1 1-2.4 1-4 .2-1.5.3-3 .3-5 0-1.7-.2-3.3-.4-4.8zm32 25.8c-2.4 0-4.5-.3-6.2-1-1.6-.7-3-1.7-4-2.8-1.2-1.2-2-2.5-2.4-4-.5-1.4-.8-3-.8-4.4V0h11.4v45.6c0 1.3.3 2.3 1 3 .5.6 1.5 1 3 1h.7v9h-2.8zm15-17c0 2.7.7 5 2.4 6.8 1.6 1.7 4 2.6 7 2.6 2.4 0 4.3-.4 5.7-1 1.4-.7 2.8-1.7 4.2-3l6.5 6.2-3.3 3c-1 .7-2.3 1.4-3.7 2-1.3.6-2.7 1-4.3 1.3-1.5.3-3.3.5-5.3.5-2.6 0-5-.3-7.6-1-2.4-.6-4.5-1.8-6.4-3.5-2-1.7-3.4-4-4.5-6.7-1.2-3-1.7-6.5-1.7-10.8 0-3.5.5-6.6 1.4-9.3 1-2.8 2.2-5 4-7 1.5-1.8 3.5-3.2 6-4.2 2.2-1 4.8-1.4 7.6-1.4 3 0 5.7.6 8 1.7 2.5 1 4.5 2.4 6 4.2 1.7 1.7 3 4 3.8 6.4.8 2.5 1.2 5.2 1.2 8.2v4.8h-27.3zm16.2-10l-.7-2c-.5-1.3-1.4-2.4-2.6-3.2-1.2-1-2.8-1.3-4.6-1.3-1.8 0-3.4.4-4.6 1.3-1.2.8-2 2-2.7 3l-.7 2.3-.3 2.5H156c0-1 0-1.7-.2-2.4zm17 25v-9c0-.4.2-.7.6-.7h8.8c.4 0 .7.3.7.7v9c0 .3-.4.6-.8.6h-8.8c-.4 0-.7-.2-.7-.6zM190.2 58.5V16h10.3v42.5h-10.3M240 52.2c-1 1.7-2.2 3.2-3.8 4.4-1.7 1-3.6 2-5.8 2.5-2.3.7-4.7 1-7.2 1-1.7 0-3.3 0-5-.2-1.5-.2-3-.4-4.7-1-1.5-.3-3-1-4.4-1.7-1.3-.7-2.7-1.8-4-3.2l7-7c1.7 1.8 3.6 3 5.7 3.4 2 .4 4 .7 5.7.7 1 0 1.8-.2 2.7-.4 1-.2 2-.4 2.6-.8.7-.3 1.3-.8 1.7-1.3.4-.6.7-1.3.7-2 0-1.2-.4-2-1-2.8-.7-.7-2-1-3.6-1.3l-6.8-.6c-4-.4-7-1.5-9.2-3.4-2.2-1.8-3.3-4.7-3.3-8.6 0-2.2.6-4.2 1.5-6 1-1.6 2-3 3.6-4.2 1.6-1 3.4-2 5.4-2.6 2-.6 4-1 6.3-1 3.3 0 6.4.5 9 1.2 3 .7 5.3 2 7.4 4l-6.5 6.6c-1.3-1-2.8-2-4.5-2.3-1.8-.4-3.6-.6-5.5-.6-2 0-3.7.3-4.7 1-1 1-1.4 2-1.4 3 0 .4 0 .8.2 1.2 0 .4.3.8.6 1 .4.5.8.8 1.4 1 .6.3 1.3.5 2.3.6l6.7.6c4.3.4 7.4 1.7 9.6 4 2 2 3.2 5 3.2 8.6 0 2.4-.5 4.4-1.4 6.2zM59.3 11.7h-8.6c-.5 0-.8-.4-.8-.8V2.2c0-.5.3-.8.8-.8h8.6c.4 0 .8.3.8.8V11c0 .4-.4.7-.8.7zm140.4 0H191c-.4 0-.8-.4-.8-.8V2.2c0-.5.4-.8 1-.8h8.4c.5 0 1 .3 1 .8V11c0 .4-.5.7-1 .7z"/>
        </svg>
    </div>
    <div class="small-6 columns">
    <a href="#" class="version-button">{{ substr($verses->first()->bible_id,3,3) }}</a>
    <a href="#" class="chapter-button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 34 24">
            <path d="M.8 4.5v18.3s8-5.7 14.8 0H17V3C6.2-4 .8 4.6.8 4.6zM15 6v12.5c-6.2-3-11.2-.5-11.2-.5V6s2.5-2.4 5.5-2.4S15 6 15 6zm2-3v19.7h1.3c6.7-5.5 15 0 15 0v-18S27.6-4 17 3zm13.2 15s-5-2.4-11.2.5V6s2.7-2.4 5.6-2.4c3 0 5.6 2.3 5.6 2.3v12z"/>
            <path fill="#FFF" d="M.8 5.5v18.3s8-5.7 14.8 0H17V4C6.2-3 .8 5.6.8 5.6zM15 7v12.5c-6.2-3-11.2-.5-11.2-.5V7s2.5-2.4 5.5-2.4S15 7 15 7zm2-3v19.7h1.3c6.7-5.5 15 0 15 0v-18S27.6-3 17 4zm13.2 15s-5-2.4-11.2.5V7s2.7-2.4 5.6-2.4c3 0 5.6 2.3 5.6 2.3v12z"/>
        </svg>
    </a>
    <form id="search-form" method="post" action="/search">
        {{ csrf_field() }}
        <input class="search" type="text" name="search" placeholder="Romanos 10:17 or Jesus">
        <input type="hidden" name="bible_id" id="volume" value="{{ $verses->first()->bible_id }}">
        <button class="search-button" type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="-12.8 -13.9 32.9 33.8">
                <path opacity=".8" fill="#231F20" d="M18.4 13.5L10 6c1.5-1.8 2.3-4.8 2.3-7.5C12.3-8 6.6-13-.2-13s-12 5-12 11.5S-7 10.3 0 10.3c2 0 4.3-.4 5.8-1l8.5 8c.6.7 1.5.7 2 0l2-1.8c.6-.6.6-1.4 0-2zM0 5.5c-4 0-7.2-3-7.2-7 0-3.7 3.3-7 7.3-7 4.3 0 7.5 3.3 7.5 7 0 4-3.2 7-7.3 7z"/>
                <path fill="#FFF" d="M18.4 14.5L10 7c1.5-1.8 2.3-4.8 2.3-7.5C12.3-7 6.6-12-.2-12s-12 5-12 11.5S-7 11.3 0 11.3c2 0 4.3-.4 5.8-1l8.5 8c.6.7 1.5.7 2 0l2-1.8c.6-.6.6-1.4 0-2zM0 6.5c-4 0-7.2-3-7.2-7 0-3.7 3.3-7 7.3-7 4.3 0 7.5 3.3 7.5 7 0 4-3.2 7-7.3 7z"/>
            </svg>
        </button>
    </form>
    </div>
    <div class="small-3 columns">
    <a href="#" class="font-button">Aa</a>
    </div>
</nav>

@if(in_array(($verses->first()->chapter_number - 1), $bibleNavigation[$verses->first()->book_id]->pluck('chapter_number')->ToArray())) <a href="/read/{{ $verses->first()->bible_id }}/{{ $verses->first()->book_id }}/{{ $verses->first()->chapter_number - 1 }}" class="sideNav left"><div class="chevron left"></div></a> @endif
@if(in_array(($verses->first()->chapter_number + 1), $bibleNavigation[$verses->first()->book_id]->pluck('chapter_number')->ToArray())) <a href="/read/{{ $verses->first()->bible_id }}/{{ $verses->first()->book_id }}/{{ $verses->first()->chapter_number + 1 }}" class="sideNav right"> <div class="chevron right"> </div></a> @endif

<main class="small-10 medium-9 large-6 columns centered">
    <article class="reader">
        <header>
            <div id="settings-panel" class="panel no-fouc"
                 data-direction="right"
                 data-clickSelector=".font-button">
                <ul class="list">
                    <li>Fonts
                        <ul class="fonts">
                            <li><a style="font-family: Helvetica" data-font="Helvetica">Helvetica</a></li>
                            <li><a style="font-family: Verdana" data-font="Verdana">Verdana</a></li>
                            <li><a style="font-family: Palatino" data-font="Palatino">Palatino</a></li>
                            <li><a style="font-family: Georgia" data-font="Georgia">Georgia</a></li>
                        </ul></li>
                    <li>Size
                        <ul class="sizer">
                            <li><a data-size=".75rem">small</a></li>
                            <li><a data-size="1rem">medium</a></li>
                            <li><a data-size="1.5rem">large</a></li>
                            <li><a data-size="2rem">extra large</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div id="navigation-panel" class="panel no-fouc"
                 data-direction="top"
                 data-clickSelector=".chapter-button">
                <ul class="list">
                    @foreach($bibleNavigation as $bookID => $chapters)
                    <div class="book">{{ $chapters->first()->book->currentTranslation->name ?? $chapters->first()->book->name }}</div>
                    <div class="chapters @if($loop->first) active @endif">
                        @foreach($chapters as $chapter)
                            <div class="small-2 columns chapter"><a href="/read/{{ $chapter->bible_id }}/{{ $chapter->book_id }}/{{ $chapter->chapter_number }}">{{ $chapter->chapter_number }}</a></div>
                        @endforeach
                    </div>
                    @endforeach
                </ul>
            </div>
            <div id="bible-panel" class="panel no-fouc"
                 data-direction="left"
                 data-clickSelector=".version-button">
                <input class="search" placeholder="Search" />
                <ul class="list">
                    @foreach($bibleLanguages as $languageName => $bibles)
                        <li class="language">{{ $languageName }}</li>
                        @foreach($bibles as $bible)
                            <li><a href="/read/{{ $bible->id }}/" class="name">{{ $bible->currentTranslation->name }}<small>{{ $bible->id }}</small></a></li>
                        @endforeach
                    @endforeach
                </ul>
            </div>
        </header>
        @if(!$query)
            <h2>{{ $verses->first()->book->currentTranslation->name ?? $verses->first()->book->name }} {{ $verses->first()->chapter_number }}</h2>
        @foreach($verses as $verse)
           <sup>{{ $verse->verse_start }}@if(isset($verse->verse_end))-{{ $verse->verse_end }}@endif</sup> {{ $verse->verse_text }}
        @endforeach
        @else
            <div class="results">
                <h2>Results</h2>
                @foreach($verses as $verse)
                    <div class="result">
                    <a href="/read/{{ $verse->bible_id }}/{{ $verse->book_id }}/{{ $verse->chapter_number }}"><strong>{{ $verse->book->currentTranslation->name ?? $verse->book->name }} {{ $verse->chapter_number }}:{{ $verse->verse_start }}</strong><br> {!! str_replace($query,"<b>$query</b>",$verse->verse_text) !!}</a>
                    </div>
                @endforeach
            </div>
        @endif
    </article>
</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js"></script>
<script src="/js/reader.js"></script>
<script>

    // Filters
    var options = {valueNames: [ 'name', 'language' ]};
    new List('bible-panel', options);

    var container = $('.reader');
    if (!container.hasClass('scotchified')) {
        container.wrapInner('<div class="scotch-panel-wrapper"><div class="scotch-panel-canvas"></div></div>').addClass('scotchified');
    }

    // Scotch
    $('#bible-panel').scotchPanel({
        containerSelector:'.reader',
        forceMinHeight:true,
        height: '100%',
        direction:'left',
        duration:300,
        transition:'ease',
        clickSelector:'.version-button',
        distanceX:'100%',
        enableEscapeKey:true
    });
    $('#navigation-panel').scotchPanel({
        containerSelector:'.reader',
        forceMinHeight:true,
        height: '100%',
        direction:'top',
        duration:300,
        transition:'ease',
        clickSelector:'.chapter-button',
        distanceX:'100%',
        enableEscapeKey:true
    });
    $('#settings-panel').scotchPanel({
        containerSelector:'.reader',
        forceMinHeight:true,
        height: '100%',
        direction:'right',
        duration:300,
        transition:'ease',
        clickSelector:'.font-button',
        distanceX:'40%',
        enableEscapeKey:true
    });

    // Font
    $('.fonts a').click(function() {
        $('.reader').css('font-family',$(this).data('font'));
    });
    $('.sizer a').click(function() {
        $('.reader').css('font-size',$(this).data('size'));
    });




</script>
</body>
</html>