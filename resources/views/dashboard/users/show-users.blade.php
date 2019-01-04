@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', [
        'title' => 'All Users'
    ])

    <div class="container">
        <div class="columns">
            <a href="/users/create"><i class="fa fa-fw fa-user-plus" aria-hidden="true"></i> @lang('usersmanagement.buttons.create-new')</a>
            <a href="/users/deleted"><i class="fa fa-fw fa-group" aria-hidden="true"></i> @lang('usersmanagement.show-deleted-users')</a>
            <div class="row">

                <form method="POST" action="{{ route('search-users') }}" id="search_users">
                    {!! csrf_field() !!}
                    <div class="field has-addons">
                        <div class="control">
                            <input class="input" type="text" name="user_search_box" placeholder="Find a User">
                        </div>
                        <div class="control">
                            <a class="button is-info">Search</a>
                        </div>
                    </div>
                </form>

            </div>

        </div>

        <div class="columns">

                    <div class="card-body">

                        <div class="table-responsive users-table">
                            <table class="table table-striped table-sm data-table">
                                <thead class="thead">
                                    <tr>
                                        <th>@lang('usersmanagement.users-table.id')</th>
                                        <th>@lang('usersmanagement.users-table.name')</th>
                                        <th class="hidden-xs">@lang('usersmanagement.users-table.email')</th>
                                        <th class="hidden-xs">@lang('usersmanagement.users-table.fname')</th>
                                        <th class="hidden-xs">@lang('usersmanagement.users-table.lname')</th>
                                        <th>@lang('usersmanagement.users-table.role')</th>
                                        <th class="hidden-sm hidden-xs hidden-md">@lang('usersmanagement.users-table.created')</th>
                                        <th class="hidden-sm hidden-xs hidden-md">@lang('usersmanagement.users-table.updated')</th>
                                        <th>@lang('usersmanagement.users-table.actions')</th>
                                        <th class="no-search no-sort"></th>
                                        <th class="no-search no-sort"></th>
                                    </tr>
                                </thead>
                                <tbody id="users_table">
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{$user->id}}</td>
                                            <td>{{$user->name}}</td>
                                            <td class="hidden-xs"><a href="mailto:{{ $user->email }}" title="email {{ $user->email }}">{{ $user->email }}</a></td>
                                            <td class="hidden-xs">{{$user->first_name}}</td>
                                            <td class="hidden-xs">{{$user->last_name}}</td>
                                            <td>
                                                @foreach ($user->roles as $user_role)
                                                    @if ($user_role->name == 'User')
                                                        @php $badgeClass = 'primary' @endphp
                                                    @elseif ($user_role->name == 'Admin')
                                                        @php $badgeClass = 'warning' @endphp
                                                    @elseif ($user_role->name == 'Unverified')
                                                        @php $badgeClass = 'danger' @endphp
                                                    @else
                                                        @php $badgeClass = 'default' @endphp
                                                    @endif
                                                    <span class="badge badge-{{$badgeClass}}">{{ $user_role->name }}</span>
                                                @endforeach
                                            </td>
                                            <td class="hidden-sm hidden-xs hidden-md">{{$user->created_at}}</td>
                                            <td class="hidden-sm hidden-xs hidden-md">{{$user->updated_at}}</td>
                                            <td>
                                                <form action="users/{{ $user->id }}" method="POST">
                                                    {{ method_field('DELETE') }}
                                                    {{ csrf_field() }}
                                                    <input type="input">
                                                </form>

                                                {!! Form::open(array('url' => , 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                                    {!! Form::hidden('_method', 'DELETE') !!}
                                                    {!! Form::button(trans('usersmanagement.buttons.delete'), array('class' => 'btn btn-danger btn-sm','type' => 'button', 'style' =>'width: 100%;' ,'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete User', 'data-message' => 'Are you sure you want to delete this user ?')) !!}
                                                {!! Form::close() !!}
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-success btn-block" href="{{ URL::to('users/' . $user->id) }}" data-toggle="tooltip" title="Show">
                                                    @lang('usersmanagement.buttons.show')
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-info btn-block" href="{{ URL::to('users/' . $user->id . '/edit') }}" data-toggle="tooltip" title="Edit">
                                                    @lang('usersmanagement.buttons.edit')
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tbody id="search_results"></tbody>
                                @if(config('usersmanagement.enableSearchUsers'))
                                    <tbody id="search_results"></tbody>
                                @endif

                            </table>

                            @if(config('usersmanagement.enablePagination'))
                                {{ $users->links() }}
                            @endif

                        </div>
                    </div>
                </div>

        </div>
    </div>

    @include('layouts.partials.modals.modal-delete')

@endsection