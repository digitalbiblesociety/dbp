@extends('layouts.app')

@section('head')
    <style>
        section[role="banner"] {
            background: #222;
            position: relative;
        }

        section[role="banner"] h1 {
            color:#FFF;
            padding:70px 10px;
        }

        section[role="banner"] small {
            display: block;
            padding:20px;
        }

        section[role="banner"] .stat {
            color:#FFF;
        }

        section[role="banner"] .set_type {
            position: absolute;
            top:5px;
            right:5px;
            color:#e2e2e2;
            opacity: .5;
        }
    </style>
@endsection

@section('content')
    <section role="banner">
        <h1 class="text-center">{{ $fileset->name }}<small>{{ $fileset->id }}</small></h1>
        <b class="set_type">{{ $fileset->set_type }}</b>
    </section>
        <div class="row">
            <div class="button-group medium-6 columns centered expanded">
                <a class="button" href="{{ route('view_bible_filesets.edit', $fileset->id) }}">Edit</a>
                <a class="button" href="{{ route('view_bible_filesets.create') }}">Create New</a>
                <a class="button" href="{{ route('view_bible_filesets_permissions.create', $fileset->id) }}">Add Permission</a>
            </div>
        </div>
    <div class="row">
        <div class="medium-6 columns">
            <table class="table" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td>User Name</td>
                    <td>Access Level</td>
                </tr>
                </thead>
                <tbody>
                    @foreach($fileset->permissions as $permission)
                        <tr>
                            <td>{{ $permission->user->name }}</td>
                            <td>{{ $permission->access_level }}</td>
                            <td><a href="{{ route('view_bible_filesets_permissions.edit', [ 'id' => $fileset->id, 'permission' => $permission->id]) }}">Edit Permission</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="medium-6 columns">
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
    </div>
@endsection