@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', ['title' => 'Bibles'])
    <div class="row">
    <table class="table" cellspacing="0" width="100%" data-route="bibles">
        <thead>
            <tr>
                <th>ID</th>
                <th>Copyright</th>
                <th>Name</th>
                <th>Vernacular Name</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    </div>
@endsection