@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', [
        'title' => 'Bibles',
        'breadcrumbs' => [
            route('reader.languages') => 'Reader',
            '#' => 'Bibles'
        ]
    ])

    <div class="container box">

        @foreach($filesets as $fileset)
            <a href="{{ route('reader.books',['fileset_id' => $fileset->id]) }}">
                @if($fileset->bible->first())
                    @foreach($fileset->bible->first()->translations as $translation)
                        {{ $translation->name }}
                    @endforeach
                @else
                    {{ $fileset->id }}
                @endif
            </a>
        @endforeach

    </div>

@endsection