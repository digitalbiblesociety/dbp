@extends('layouts.app')

@section('content')

    <div class="row">
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
        @foreach($user->permissions as $permission)
            <tr>
                <td><a class="button expanded" href="{{ route('view_bible_filesets_permissions.show', ['id' => $permission->fileset->id,'permission' => $permission->id]) }}">View</a></td>
                <td>{{ $permission->access_level }}</td>
                <td>{{ $permission->fileset->bible->id }}</td>
                <td>{{ $permission->fileset->bible->currentTranslation->name }}</td>
                <td>{{ $permission->fileset->bible->vernacularTranslation->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>

@endsection