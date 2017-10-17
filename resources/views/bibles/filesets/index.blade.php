@extends('layouts.app')

@section('content')
    <h1 class="text-center">File Sets</h1>
    <table class="table" cellspacing="0" width="100%" data-route="bibles/filesets">
        <thead>
        <tr>
            <td>id</td>
            <td>Name</td>
            <td>Set Type</td>
            <td>Organization</td>
            <td>Bible</td>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@endsection