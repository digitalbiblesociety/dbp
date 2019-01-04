@extends('layouts.app')

@php
    switch (config('laravelPhpInfo.bootstapVersion')) {
        case '4':
            $containerClass = 'card';
            $containerHeaderClass = 'card-header';
            $containerBodyClass = 'card-body';
            break;
        case '3':
        default:
            $containerClass = 'panel panel-default';
            $containerHeaderClass = 'panel-heading';
            $containerBodyClass = 'panel-body';
    }
    $bootstrapCardClasses = (is_null(config('laravelPhpInfo.bootstrapCardClasses')) ? '' : config('laravelPhpInfo.bootstrapCardClasses'));
@endphp


@if(config('laravelPhpInfo.usePHPinfoCSS'))
    <style type="text/css" media="screen">
        .php-info pre {
            margin: 0;
            font-family: monospace;
        }
        .php-info a:link {
            color: #009;
            text-decoration: none;
            background-color: #ffffff;
        }
        .php-info a:hover {
            text-decoration: underline;
        }
        .php-info table {
            border-collapse: collapse;
            border: 0;
            width: 100%;
            box-shadow: 1px 2px 3px #ccc;
        }
        .php-info .center {
            text-align: center;
        }
        .php-info .center table {
            margin: 1em auto;
            text-align: left;
        }
        .php-info .center th {
            text-align: center !important;
        }
        .php-info td {
            border: 1px solid #666;
            font-size: 75%;
            vertical-align: baseline;
            padding: 4px 5px;
        }
        .php-info th {
            border: 1px solid #666;
            font-size: 75%;
            vertical-align: baseline;
            padding: 4px 5px;
        }
        .php-info h1 {
            font-size: 150%;
        }
        .php-info h2 {
            font-size: 125%;
        }
        .php-info .p {
            text-align: left;
        }
        .php-info .e {
            background-color: #ccf;
            width: 50px;
            font-weight: bold;
        }
        .php-info .h {
            background-color: #99c;
            font-weight: bold;
        }
        .php-info .v {
            background-color: #ddd;
            max-width: 50px;
            overflow-x: auto;
            word-wrap: break-word;
        }
        .php-info .v i {
            color: #999;
        }
        .php-info img {
            float: right;
            border: 0;
        }
        .php-info hr {
            width: 100%;
            background-color: #ccc;
            border: 0;
            height: 1px;
        }
    </style>
@endif


@section('content')

    @include('layouts.partials.banner', [
        'title' => 'PHP '.$phpInfo["Core"]["PHP Version"],
        'breadcrumbs' => [
            '/'     => trans('about.home'),
            '#'     => trans('about.relations_title')
        ]
    ])

    <div class="container">
        <div class="box">

            @foreach($phpInfo['Core'] as $key => $value)
                <p><b>{{ $key }}:</b>
                    @if(is_array($value))
                        {{ implode(',', $value) }}
                    @else
                        {{ $value }}
                    @endif
                </p>
            @endforeach

            @unset($phpInfo['Core'])

            <div class="columns is-multiline">
                @foreach($phpInfo as $title => $values)
                    <div class="column">
                    <h3 class="is-size-4">{{ $title }}</h3>
                    @foreach($values as $key => $value)
                        <p><b>{{ $key }}:</b>
                        @if(is_array($value))
                            {{ implode(',', $value) }}
                        @else
                            {{ $value }}
                        @endif
                        </p>
                    @endforeach
                    </div>
                @endforeach
            </div>

        </div>
    </div>
@endsection
