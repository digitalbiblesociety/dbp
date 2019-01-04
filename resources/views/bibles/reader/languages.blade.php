@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', [
        'title' => 'Languages',
        'breadcrumbs' => [
            'Reader' => '#'
        ]
    ])

    <div class="container box">
        <table class="table" width="100%">
            <thead>
                <th>Name</th>
                <th>Autonym</th>
                <th>Bibles Count</th>
            </thead>
            <tbody>
            @foreach($languages as $language)
                <tr>
                    <td><a href="{{ route('reader.bibles',['id' => $language->id]) }}">{{ $language->name }}</a></td>
                    <td><a href="{{ route('reader.bibles',['id' => $language->id]) }}">{{ $language->autonym }}</a></td>
                    <td>{{ $language->bibles_count }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection