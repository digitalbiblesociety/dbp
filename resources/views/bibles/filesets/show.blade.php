@extends('layouts.app')

@section('head')
    <style>
        section[role="banner"] {
            background: #444;
            position: relative;
            padding:20px;
        }
        .input-group    {
            margin-bottom:0
        }
        .stat {
            font-size:1.5rem;
            line-height:1;
            min-height:120px;
            padding:20px;
            background:#eee;
            color:#222;
            margin-top:20px;
            display:flex;
            align-items:center;
            justify-content:center;
        }
    </style>
@endsection

@section('content')
    <section role="banner">
        <div class="row">
            <div class="medium-3 medium-6 columns"><div class="stat" style="color:{{ $fileset->organization->primaryColor }}">{{ $fileset->organization->currentTranslation->name }}</div><b>Owning Organization</b></div>
            <span class="hide-for-small-only">
            <div class="small-6 medium-3 large-2 columns"><div class="stat">{{ $fileset->files->count() }}</div><b>File Count</b></div>
            <div class="small-6 medium-3 large-2 columns"><div class="stat">{{ $fileset->set_type }}</div><b>Set Type</b></div>
            <div class="small-6 medium-3 large-2 columns"><div class="stat">{{ $fileset->id }}</div><b>Set ID</b></div>
            <div class="small-6 medium-3 large-3 columns"><div class="stat">{{ ($fileset->hidden) ? "Visible" : "Hidden" }}</div><b>Visibility</b></div>
            <div class="small-6 medium-3 large-3 columns"><div class="stat">{{ $fileset->created_at->toFormattedDateString() }}</div><b>Created On</b></div>
            <div class="small-6 medium-3 large-3 columns"><div class="stat">{{ $fileset->updated_at->toFormattedDateString() }}</div><b>Last Updated</b></div>
            <div class="small-6 medium-3 large-3 columns"><div class="stat">{{ $fileset->responseTime ?? "A couple of days... probably? Maybe ".rand(3,9)."?" }}</div><b>Average Response Time</b></div></span>
        </div>
    </section>

<div class="row">
    <ul class="tabs" data-tabs id="fileset-tabs">
        <li class="tabs-title"><a data-tabs-target="permissions" href="#permissions" aria-selected="true">Permissions</a></li>
        <li class="tabs-title"><a data-tabs-target="files" href="#files">Files</a></li>
    </ul>

    <div class="tabs-content" data-tabs-content="fileset-tabs">
        <div class="tabs-panel" id="files">
            <table class="table" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td>File Name</td>
                    <td>Reference</td>
                </tr>
                </thead>
                <tbody>
                @foreach($fileset->files as $file)
                    <tr>
                        <td><a href="#">{{ $file->file_name }}</a></td>
                        <td>{{ $file->book_id }}
                            @if(($file->chapter_end != null) AND ($file->chapter_end != $file->chapter_start))
                                {{ $file->chapter_start.':'.$file->verse_start.'-'. $file->chapter_end .':'.$file->verse_end }}
                            @elseif(isset($file->verse_end))
                                {{ $file->chapter_start.':'.$file->verse_start.'-'.$file->verse_end }}
                            @else
                                {{ $file->chapter_start }}
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="tabs-panel is-active" id="permissions">
            <table class="table" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td>User Name</td>
                    <td>Access Level</td>
                    <td>Created</td>
                    <td>Updated</td>
                    @can('update', $fileset)
                        <td>Update</td>
                    @endcan
                </tr>
                </thead>
                <tbody>
                @foreach($fileset->permissions as $permission)
                    <tr class="callout {{ ($permission->access_level == "denied") ? "alert" : "" }} {{ ($permission->access_level == "") }}">
                        <td>{{ $permission->user->name }}</td>
                        <td>{{ $permission->access_level }}</td>
                        <td>{{ $permission->created_at->toFormattedDateString() }}</td>
                        <td>{{ $permission->created_at->toFormattedDateString() }}</td>
                        @can('update', $fileset)
                            <td>
                                <form method="POST" action="{{ route('view_bible_filesets_permissions.update', [ 'id' => $fileset->id, 'permission' => $permission->id]) }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="permission_id" value="{{ $permission->id }}">
                                    <input type="hidden" name="_method" value="PUT">
                                    <div class="input-group">
                                        <span class="input-group-label">Access</span>
                                        <select name="access_level" class="input-group-field access_level">
                                            <option value="full">Source Files</option>
                                            <option value="online">API Only</option>
                                            <option value="denied">Deny Request</option>
                                        </select>
                                        <div class="input-group-button">
                                            <input type="submit" class="button access_button" value="Grant">
                                        </div>
                                    </div>
                                </form>
                            </td>
                        @endcan
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('footer')
    <script>
        $('.access_level').change(function() {
            var value = $(".access_level option:selected").val();
            $button = $(this).next().children('.access_button');
            if(value == "denied") {
                $button.addClass('alert');
                $button.attr('value','Deny');
            } else {
                $button.removeClass('alert');
                $button.attr('value','Grant');
            }
        });
    </script>
@endsection