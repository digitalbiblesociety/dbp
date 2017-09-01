@extends('layouts.app')

@section('content')
    <h1 class="text-center">Countries</h1>

    <table class="table" cellspacing="0" width="100%" data-route="countries">
        <thead>
        <tr>
            <th>{{ trans('fields.alternativeNames') }}</th>
            <th>{{ trans('fields.name') }}</th>
            <th>{{ trans('fields.id') }}</th>
            <th>{{ trans('fields.iso') }}</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
@endsection