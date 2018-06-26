@extends('layouts.app')

@section('head')
    <title>{{ trans('titles.401_page_title') }} | {{ trans('fields.siteTitle') }}</title>
    <meta name="description" content="{{ trans('titles.401_description') }}">

    <style>
        .error-401 h1 {
            font-size:2rem;
            max-width:500px;
            margin:0 auto;
            letter-spacing: 2px;
        }

        .error-401 {

        }


        .error-401-image {
            margin:0 auto;
            display: block;
            width:120px;
        }

        .reference {
            position: absolute;
            top:145px;
            text-align: center;
            width:600px;
            left:50%;
            margin-left:-300px;
            opacity: .1;
            color:#FFF;
            font-size:2rem;
        }
    </style>
@endsection

@section('content')

    @include('layouts.partials.banner', [
        'banner_class'  => 'error-401',
        'title'         =>  $message ?? "401 Error",
        'image'         => '/img/banners/sword.png',
        'image_class'   => 'error-401-image'
    ])

<div class="reference">      וַיֹּ֣אמֶר ׀ יְהוָ֣ה אֱלֹהִ֗ים הֵ֤ן הָֽאָדָם֙ הָיָה֙ כְּאַחַ֣ד מִמֶּ֔נּוּ לָדַ֖עַת ט֣וֹב וָרָ֑ע וְעַתָּ֣ה ׀ פֶּן־יִשְׁלַ֣ח יָד֗וֹ וְלָקַח֙ גַּ֚ם מֵעֵ֣ץ הַֽחַיִּ֔ים וְאָכַ֖ל וָחַ֥י לְעֹלָֽם׃  וַֽיְשַׁלְּחֵ֛הוּ יְהוָ֥ה אֱלֹהִ֖ים מִגַּן־עֵ֑דֶן לַֽעֲבֹד֙ אֶת־הָ֣אֲדָמָ֔ה אֲשֶׁ֥ר לֻקַּ֖ח מִשָּֽׁם׃  וַיְגָ֖רֶשׁ אֶת־הָֽאָדָ֑ם וַיַּשְׁכֵּן֩ מִקֶּ֨דֶם לְגַן־עֵ֜דֶן</div>

@endsection