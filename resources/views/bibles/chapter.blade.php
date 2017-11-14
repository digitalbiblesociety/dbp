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
    <meta property="og:url" content="{{ env('APP_URL') }}/{{ $verses->first()->bible_id }}/{{ $verses->first()->book_id }}/{{ $verses->first()->chapter_number }}" />
    <meta property="og:image" content="{{ env('APP_URL') }}/images/FB-post-icon.png?cr=1" />
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
    <link rel="apple-touch-icon" href="{{ env('APP_URL') }}/images/icons/apple-touch-icon-152.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ env('APP_URL') }}/images/icons/apple-touch-icon-76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="t{{ env('APP_URL') }}/images/icons/apple-touch-icon-120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ env('APP_URL') }}/images/icons/apple-touch-icon-152.png">
    <link rel="apple-touch-startup-image" href="{{ env('APP_URL') }}/images/spinner.gif" />
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />
    <!--[if IE 8]> <link href="{{ env('APP_URL') }}/css/ie8.css?cr=1" rel="stylesheet" type="text/css" media="screen" /> -->
    <style>
        body {
            min-height:100vh;
        }
        .panel,
        .no-fouc {visibility: hidden!important;}

        nav {
            background:#A14;
            height:60px;
        }

        nav input,
        nav a {
            float:left;
        }

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
        }


        .font-button {
            float:right;
            margin-right:15px;
        }

        .font-button:hover {
            color:#FFF;
            font-weight:bold;
        }

        .version-button {
            margin:5px;
            width:140px;
            height:50px;
            color:#fff;
            text-indent:55px;
            line-height:50px;
            background:url("https://bible.cloud/images/covers/110x170/{{ $verses->first()->bible_id }}.jpg") no-repeat left center;
            background-size: 35px 50px;
        }

        nav #search-form input {
            width:calc(100% - 80px);
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

    </style>
</head>
<body>
<header>
<div id="settings-panel" class="panel"
     data-containerSelector="body"
     data-direction="right"
     data-clickSelector=".font-button">
    <ul class="list">
        <li>Fonts
        <ul class="fonts">
            <li><a data-font="Helvetica">Helvetica</a></li>
            <li><a data-font="Verdana">Verdana</a></li>
            <li><a data-font="Palatino">Palatino</a></li>
            <li><a data-font="Georgia">Georgia</a></li>
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
<div id="navigation-panel" class="panel"
     data-containerSelector="body"
     data-direction="top"
     data-clickSelector=".chapter-button">
    <ul class="list">
        @foreach($bibleNavigation as $bookName => $chapters)
            @if($loop->iteration == 1 OR ($loop->iteration % 3) == 0) <div class="row"> @endif
            <div class="medium-4 columns">
            <div class="book row">{{ $bookName }}</div>
            <div class="row">
                <div class="chapters">
            @foreach($chapters as $chapter)
                    <div class="small-2 columns chapter"><a href="/read/{{ $chapter->bible_id }}/{{ $chapter->book_id }}/{{ $chapter->chapter_number }}">{{ $chapter->chapter_number }}</a></div>
            @endforeach
                </div>
            </div>
            </div>
                @if(($loop->iteration % 3) == 0) </div> @endif
        @endforeach
    </ul>
</div>
<div id="bible-panel" class="panel"
     data-containerSelector="body"
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
<nav>
    <div class="small-3 columns">
    <a href="#" class="version-button">{{ substr($verses->first()->bible_id,3) }}</a>
    </div>
    <div class="small-6 columns">
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
<main class="small-10 medium-7 columns centered">
    <article class="reader">
        @if(!$query)
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

    // Scotch
    $('#bible-panel').scotchPanel();
    $('#navigation-panel').scotchPanel();
    $('#settings-panel').scotchPanel();

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