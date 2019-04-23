@extends('layouts.app')

@section('head')

@endsection

@section('content')

    @include('layouts.partials.banner', [
        'title' => $bible->translations->where('iso', 'eng')->first()->name ?? '',
        'subtitle' =>  $bible->translations->where('vernacular', 1)->first()->name ?? '',
        'breadcrumbs' => [
            '/' => 'Home',
            '/' => 'Wiki',
            route('wiki_bibles.all') => 'Bibles',
            '#'       => $bible->id,
        ]
    ])
<div class="container">
    <div class="columns">
    <aside class="column is-4">
        <div><b>id:</b> {{ $bible->id }}</div>
        <div><b>iso:</b> {{ $bible->iso }}</div>
        <div><b>language_id:</b> {{ $bible->language_id }}</div>
        <div><b>versification:</b> {{ $bible->versification }}</div>
        <div><b>numeral_system_id:</b> {{ $bible->numeral_system_id }}</div>
        <div><b>date:</b> {{ $bible->date }}</div>
        <div><b>scope:</b> {{ $bible->scope }}</div>
        <div><b>script:</b> {{ $bible->script }}</div>
        <div><b>derived:</b> {{ $bible->derived }}</div>
    </aside>
    <div class="column is-8">
    @foreach($bible->filesets as $fileset)
        <div class="box">
            <b>id</b>: {{ $fileset->id }}<br>
            <b>set_type_code</b>: {{ $fileset->set_type_code }}<br>
            <b>set_size_code</b>: {{ $fileset->set_size_code }}<br>
        </div>
    @endforeach
    </div>
    </div>
</div>
@endsection