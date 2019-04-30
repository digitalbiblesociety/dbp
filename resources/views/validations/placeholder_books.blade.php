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
                <th>Bible ID</th>
                <th>Books</th>
            </tr>
            </thead>
            <tbody>
            @foreach($books as $bible_id => $books)
                <tr>
                    <td>{{ $bible_id }}</td>
                    <td>{!! $books->pluck('book_id')->implode(', ')  !!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

@endsection