@extends('layouts.app')

@section('template_title')
    {{ trans('app.activeUsers') }}
@endsection

@section('content')

    <users-count :registered={{ $users }} ></users-count>

@endsection
