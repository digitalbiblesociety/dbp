@extends('layouts.app')

@section('content')
    <h1 class="text-center">Bibles</h1>
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
@endsection