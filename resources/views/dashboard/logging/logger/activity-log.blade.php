@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <title>{{ trans('dashboard.logger_title') }} | DBP</title>
@endsection

@section('content')


    @include('layouts.partials.banner', [
        'title'    => trans('dashboard.logger_title'),
        'subtitle' => $totalActivities .' '. trans('dashboard.logger_subtitle')
    ])


    <div class="container">

        @include('dashboard.logging.partials.form-status')

        <form action="{{ route('clear-activity') }}">
            {{ csrf_field('DELETE') }}
            <button class="button" type="submit">{{ trans('dashboard.logger_menu_clear') }}</button>
        </form>

        @include('dashboard.logging.logger.partials.activity-table', ['activities' => $activities, 'hoverable' => true])
        @include('dashboard.logging.modals.confirm-modal', ['formTrigger' => 'confirmDelete', 'modalClass' => 'danger', 'actionBtnIcon' => 'fa-trash-o'])

    </div>

@endsection

@section('footer')

@endsection