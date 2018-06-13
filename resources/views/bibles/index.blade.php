@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', ['title' => 'Bibles'])

    <div class="row">
    <table class="table" cellspacing="0" width="100%" data-route="bibles">
        <thead>
            <tr>
                <th data-column-name="name">Name</th>
                <th data-column-name="vname">Vernacular Name</th>
                <th data-column-name="country">Country</th>
                <th data-column-name="language">Language</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    </div>
@endsection