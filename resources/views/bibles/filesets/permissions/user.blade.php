@extends('layouts.app')

@section('head')
    <style>
        .global-permissions {
            text-align: center;
            padding:2rem 0;
            font-size:1rem;
        }

        .global-permissions .permission {
            background: #f1f1f1;
            padding:2rem 0;
        }

        .global-permissions .denied b,
        .global-permissions .granted b {
            display: block;
            color: green;
            font-size:1.5rem;
        }

        .global-permissions .denied b {
            color:darkred;
        }
    </style>
@endsection

@section('content')

    @if($user->permissions->count() == 0 )
        <h2>{{ trans('auth.permissions_noneGranted_title')  }}</h2>
        <p>{{ trans('auth.permissions_noneGranted_description')  }}</p>
        <a href="">{{ trans('auth.permissions_noneGranted_action') }}</a>
    @else
    <div class="row">

        <div class="row global-permissions">
        @foreach($user->permissions->where('fileset_id',NULL) as $global_permission)
            <div class="medium-3 columns">
                <div class="permission {{ ($global_permission->access_given) ? 'granted' : 'denied' }}">
                    <b>{{ ($global_permission->access_given) ? trans('auth.permissions_granted') : trans('auth.permissions_denied') }}</b>
                    {{ $global_permission->access_type }}
                </div>
            </div>
        @endforeach
        </div>

        <table>
            <thead>
            <tr>
                <td>View</td>
                <td>Access Level</td>
                <td>Bible ID</td>
                <td>Current Translation</td>
                <td>Vernacular Translation</td>
            </tr>
            </thead>
            <tbody>
            @foreach($user->permissions->where('fileset_id','!=',NULL) as $permission)
                <tr>
                    <td><a class="button expanded" href="{{ route('view_bible_filesets_permissions.show', ['id' => @$permission->fileset->id,'permission' => @$permission->id]) }}">View</a></td>
                    <td>{{ @$permission->access_level }}</td>
                    <td>{{ @$permission->fileset->bible->id }}</td>
                    <td>{{ @$permission->fileset->bible->currentTranslation->name }}</td>
                    <td>{{ @$permission->fileset->bible->vernacularTranslation->name }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif

@endsection