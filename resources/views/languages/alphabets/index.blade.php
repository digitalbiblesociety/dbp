@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', ['title' => trans('fields.alphabets')])

    <div class="row">
        <table class="table" cellspacing="0" width="100%" data-route="alphabets">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Family</th>
                    <th>Type</th>
                    <th>Direction</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

@endsection