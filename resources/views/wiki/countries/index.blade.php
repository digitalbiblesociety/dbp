@extends('layouts.app')

@section('content')
    <h1 class="text-center">Countries</h1>
    <div class="row">
    <table class="table" cellspacing="0" width="100%" data-route="countries">
        <thead>
            <tr>
                <th data-column-name="name" data-link="codes.iso_a2">{{ trans('fields.name') }}</th>
                <th data-column-name="codes.fips">fips</th>
                <th data-column-name="codes.iso_a3">{{ trans('fields.id') }}</th>
                <th data-column-name="codes.iso_a2">{{ trans('fields.iso') }}</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    </div>
@endsection