@extends('layouts.app')


@section('content')

    @include('layouts.partials.banner', ['title' => 'Languages'])

    <div class="row">
        <table class="table" cellspacing="0" width="100%" data-route="languages">
            <thead>
            <tr>
                <th>{{ trans('fields.name') }}</th>
                <th>{{ trans('fields.iso') }}</th>
                <th>{{ trans('fields.bibles_count') }}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
@endsection