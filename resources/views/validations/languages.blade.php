@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', [
        'title' => 'Language Resources'
    ])

    <div class="container">

        @include('validations.validate-nav')

        <table class="table" width="100%">
            <thead>
            <tr>
                <td>ID</td>
                <td>Glotto id</td>
                <td>iso</td>
                <td>Backup Name</td>
                <td>Autonym</td>
            </tr>
            </thead>
            <tbody>
                @foreach($languages as $language)
                    <tr>
                        <td>{{ $language->id }}</td>
                        <td>{{ $language->glotto_id }}</td>
                        <td>{{ $language->iso }}</td>
                        <td>{{ $language->backup_name }}</td>
                        <td>{{ $language->autonym }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

@endsection