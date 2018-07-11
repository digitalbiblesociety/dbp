@extends('layouts.app')

@section('head')
    <style>
        h1 {
            text-align: center;
            font-size:2rem;
        }

        h1 small {
            display: block;
            color:#888;
        }
        table {
            margin:0 auto;
            min-width:600px;
        }
    </style>
@endsection

@section('content')

    <h1>Code Analysis <small>(<?php echo date ("F d Y H:i:s.", filemtime(storage_path('app/code_analysis.csv'))) ?>)</small></h1>
    <table>
        <head>
            <th>Key</th>
            <th>Stat</th>
        </head>
    @foreach($analysis[0] as $key => $item)
    <tr>
        <td>{{ $key }}</td>
        <td>{{ $item }}</td>
    </tr>
    @endforeach
    </table>
@endsection