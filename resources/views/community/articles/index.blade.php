@extends('layouts.app')

@section('head')
    <style>
    </style>
@endsection

@section('content')

    <nav>

    </nav>

    <main>

    </main>

@endsection

@section('footer')
    <script>
        $.getJSON('https://api.dbp.dev/articles?key=1234&v=4', function(data) {
                console.log(data);
        });
    </script>
@endsection