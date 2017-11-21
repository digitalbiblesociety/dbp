@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', ['title' => 'Bibles'])
    <div class="row">
    <table class="table" cellspacing="0" width="100%" data-route="bibles">
        <thead>
            <tr>
                <th>Name</th>
                <th>Vernacular Name</th>
                <th>Country</th>
                <th>Language</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    </div>
@endsection