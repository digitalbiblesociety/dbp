@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', ['title' => 'Create Permissions For '.$fileset->bible->currentTranslation->name.' '.$fileset->name])

    <form action="{{ route('view_bible_filesets_permissions.store', ['id' => $fileset->id]) }}" method="POST">
        <div class="medium-8 columns centered">
            {{ csrf_field() }}
            <input type="hidden" value="requesting-access" name="access_level" />
            <input type="hidden" value="{{ \Auth::user()->id }}" name="user_id" />
            <input type="hidden" value="{{ $fileset->id }}" name="bible_fileset_id" />
            <label>Access Level
                <select name="access">
                    <option value="online">Online Access Only</option>
                    <option value="source">Source Text</option>
                </select>
            </label>
            <label>Access Notes <textarea name="access_notes"></textarea></label>
            <input class="button" type="submit">
        </div>
    </form>

@endsection