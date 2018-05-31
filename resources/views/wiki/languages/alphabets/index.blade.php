@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', ['title' => trans('fields.alphabets')])

    <div class="row">
        <table class="table" cellspacing="0" width="100%" data-route="alphabets">
            <thead>
                <tr>
                    <th data-column-name="name" data-link="script">Name</th>
                    <th data-column-name="family">Family</th>
                    <th data-column-name="type">Type</th>
                    <th data-column-name="direction">Direction</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

@endsection