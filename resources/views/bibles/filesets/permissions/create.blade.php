@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', ['title' => 'Create Permissions For '.$fileset->bible->currentTranslation->name.' '.$fileset->name])

    <form action="bibles/filesets" method="POST">
        <div class="medium-8 columns centered">
            {{ csrf_field() }}
            @include('layouts.partials.datalist', ['name' => 'user', 'list' => $users, 'label' => 'User'])
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