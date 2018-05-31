@extends('layouts.app')


@section('content')

    @include('layouts.partials.banner', ['title' => 'Languages'])

    <div class="row">
        <table class="table" cellspacing="0" width="100%" data-route="languages">
            <thead>
            <tr>
                <th data-column-name="name" data-link="iso">{{ trans('fields.name') }}</th>
                <th data-column-name="iso">{{ trans('fields.iso') }}</th>
                <th data-column-name="bibles">{{ trans('fields.bibles_count') }}</th>
                <th data-column-name="filesets">{{ trans('fields.filesets_count') }}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
@endsection