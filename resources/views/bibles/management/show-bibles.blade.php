@extends('layouts.app')

@section('template_title')
    @lang('biblesmanagement.showing-all-users')
@endsection

@section('template_linked_css')
    @if(config('laravelusers.enabledDatatablesJs'))
        <link rel="stylesheet" type="text/css" href="{{ config('laravelusers.datatablesCssCDN') }}">
    @endif
    <style type="text/css" media="screen">
        .users-table {
            border: 0;
        }
        .users-table tr td:first-child {
            padding-left: 15px;
        }
        .users-table tr td:last-child {
            padding-right: 15px;
        }
        .users-table.table-responsive,
        .users-table.table-responsive table {
            margin-bottom: 0;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">

                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                @lang('biblesmanagement.showing-all-users')
                            </span>

                            <div class="btn-group pull-right btn-group-xs">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v fa-fw" aria-hidden="true"></i>
                                    <span class="sr-only">
                                        @lang('biblesmanagement.users-menu-alt')
                                    </span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="/users/create">
                                        <i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>
                                        @lang('biblesmanagement.buttons.create-new')
                                    </a>
                                    <a class="dropdown-item" href="/users/deleted">
                                        <i class="fa fa-fw fa-group" aria-hidden="true"></i>
                                        @lang('biblesmanagement.show-deleted-users')
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive users-table">
                            <table class="table table-striped table-sm data-table">
                                <caption id="user_count">
                                    {{ trans_choice('biblesmanagement.users-table.caption', 1, ['userscount' => $bibles->count()]) }}
                                </caption>
                                <thead class="thead">
                                    <tr>
                                        <th>@lang('biblesmanagement.users-table.id')</th>
                                        <th>@lang('biblesmanagement.users-table.name')</th>
                                        <th>@lang('biblesmanagement.users-table.actions')</th>
                                        <th class="no-search no-sort"></th>
                                        <th class="no-search no-sort"></th>
                                    </tr>
                                </thead>
                                <tbody id="users_table">
                                    @foreach($bibles as $bible)
                                        <tr>
                                            <td>{{$bible->id}}</td>
                                            <td>{{$bible->name}}</td>
                                            <td>
                                                {!! Form::open(array('url' => 'bibles/' . $bible->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                                    {!! Form::hidden('_method', 'DELETE') !!}
                                                    {!! Form::button(trans('biblesmanagement.buttons.delete'), ['class' => 'btn btn-danger btn-sm','type' => 'button', 'style' =>'width: 100%;' ,'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete Bible', 'data-message' => 'Are you sure you want to delete this user ?']) !!}
                                                {!! Form::close() !!}
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-success btn-block" href="{{ URL::to('bibles/' . $bible->id) }}" data-toggle="tooltip" title="Show">
                                                    @lang('biblesmanagement.buttons.show')
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-info btn-block" href="{{ URL::to('bibles/' . $bible->id . '/edit') }}" data-toggle="tooltip" title="Edit">
                                                    @lang('biblesmanagement.buttons.edit')
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tbody id="search_results"></tbody>
                                @if(config('biblesmanagement.enableSearchUsers'))
                                    <tbody id="search_results"></tbody>
                                @endif

                            </table>

                            @if(config('biblesmanagement.enablePagination'))
                                {{ $bibles->links() }}
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.partials.modals.modal-delete')

@endsection
