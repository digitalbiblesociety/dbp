@extends('layouts.app')

@section('content')

    <h2 class="text-center">{{ $project->name }}</h2>

    <div class="row">
        <table class="table" data-route="projects">
            <thead>
            <td>{{ trans('fields.name') }}</td>
            <td>{{ trans('fields.email') }}</td>
            <td>{{ trans('fields.roles') }}</td>
            </thead>
        </table>
    </div>

@endsection