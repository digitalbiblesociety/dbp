@extends('layouts.app')

@section('head')
<style>
    .banner {
        background-color: #000;
        background-image: url("/img/banners/languages.png");
        background-position: left left;
        background-repeat: no-repeat;
    }

    .banner h1 {
        color:#f0f0f0;
        font-weight:300;
        letter-spacing:1px;
        padding:100px 0;
    }

    .banner img {
        width:250px;
        margin:0 auto;
        display: block;
    }

    .dataTables_header {
        margin-top:-47px;
        background-color:rgba(0,0,0,0.5);
    }
    .dataTables_filter,
    .dataTables_length {
        width:300px;
    }

    .dataTables_filter input,
    .dataTables_length select {
        background-color:rgba(0,0,0,0.5);
        color:#f0f0f0;
        border-bottom-left-radius:0;
        border-bottom-right-radius:0;
        border-bottom:none;
    }

    .dataTables_length {
        float:right;
        width:75px;
    }
</style>
@endsection

@section('content')

    @include('layouts.partials.banner', ['title' => 'Languages'])

    <div class="row">
        <table class="table" cellspacing="0" width="100%" data-route="languages" data-invisiblecolumns="0">
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