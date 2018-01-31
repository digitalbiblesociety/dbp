@extends('layouts.app')

@section('content')

    <h2 class="text-center">Users</h2>

    <div class="row">
        <table class="table" data-route="users">
            <thead>
                <td>{{ trans('fields.name') }}</td>
                <td>{{ trans('fields.email') }}</td>
                <td>{{ trans('fields.roles') }}</td>
                <td>{{ trans('fields.organizations') }}</td>
            </thead>
        </table>
    </div>

@endsection