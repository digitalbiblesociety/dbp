@extends('layouts.app')

@section('head')
    <style>
        thead {
            display: none;
        }

        tr {
            background: #F1F1F1;
            display: block;
            width:200px;
            height:200px;
            float:left;
            text-align: center;
        }
        tr td {
            display: block;
        }

        tr td a {

        }
    </style>
@endsection

@section('content')

    <h1>Projects</h1>

    <div class="row">
        <table class="table" cellspacing="0" width="100%" data-route="projects" data-params="&all_projects=true">
            <thead>
                <tr>
                    <th data-column-name="name" data-link="id">{{ trans('fields.name') }}</th>
                    <th data-column-name="url_avatar_icon" data-image="true"></th>
                    <th data-column-name="url_avatar"></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

@endsection