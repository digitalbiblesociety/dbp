@extends('layouts.app')

@section('content')
    <h1 class="text-center">Countries</h1>
    <div class="row">
    <table class="table" cellspacing="0" width="100%" data-route="countries">
        <thead>
        <tr>
            <th>{{ trans('fields.name') }}</th>
            <th>code</th>
            <th>{{ trans('fields.id') }}</th>
            <th>{{ trans('fields.iso') }}</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    </div>
@endsection