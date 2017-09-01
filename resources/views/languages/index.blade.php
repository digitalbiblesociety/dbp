@extends('layouts.app')

@section('content')
    <div class="row">
        <h1 class="text-center">Languages</h1>
        <table class="table" cellspacing="0" width="100%" data-route="languages">
            <thead>
            <tr>
                <th>{{ trans('fields.alternativeNames') }}</th>
                <th>{{ trans('fields.name') }}</th>
                <th>{{ trans('fields.id') }}</th>
                <th>{{ trans('fields.iso') }}</th>
                <th>{{ trans('fields.bibles_count') }}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
@endsection

@section('footer')

@endsection