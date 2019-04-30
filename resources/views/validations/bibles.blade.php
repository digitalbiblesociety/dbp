@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', [
        'title' => 'Bible Resources'
    ])

    <div class="container">

    @include('validations.validate-nav')

    <table class="table" width="100%">
        <thead>
            <tr>
                <td>ID</td>
                <td>Language Id</td>
                <td>Filesets Count</td>
                <td>Links Count</td>
            </tr>
        </thead>
        <tbody>
            @foreach($bibles as $bible)
                    <tr @if(($bible->filesets_count + $bible->links_count) === 0)
                        style="background-color:#ad2462;color:#FFF" @endif
                        >
                        <td>{{ $bible->id }}</td>
                        <td>{{ $bible->language_id }}</td>
                        <td>{{ $bible->filesets_count }}</td>
                        <td>{{ $bible->links_count }}</td>
                    </tr>
            @endforeach
        </tbody>
    </table>

    @foreach($bibles as $bible)
        @if(($bible->filesets_count + $bible->links_count) === 0)
            {{ $bible->id }},
        @endif
    @endforeach
    </div>

@endsection