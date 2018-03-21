@extends('layouts.app')

@section('head')
<style>
    h3,p {text-align: center}
</style>
@endsection

@section('content')

    <h3>{{ $fileset->bible->first()->currentTranslation->name }}</h3>

    @if($fileset->permissions->count() == 0)
        <h3>Permissions</h3>
        <p>No Permissions Have been Granted for this fileset.</p>
    @endif

    <div class="medium-6 columns centered">
        <table>
            <thead>
            <tr>
                <td>Name</td>
                <td>Organization</td>
                <td>Access Granted</td>
                <td>Access Type</td>
                <td>Hash</td>
            </tr>
            </thead>
            <tbody>
                @foreach($fileset->permissions as $permission)
                    <tr>
                        <td>{{ $permission->user->first()->name }}</td>
                        <td>{{ $permission->user->first()->organizations->implode(',') }}</td>
                        <td>{{ $permission->access_granted }}</td>
                        <td>{{ $permission->access_type }}</td>
                        <td>{{ $permission->hash_id }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if(!$fileset->permissions->where('key_id',\Auth::user()->keys->first()->key)->first())
        <form class="medium-6 columns centered" action="/bibles/filesets/{{ $fileset->id }}/permissions" method="POST">
            <p>You can get request access from the copyright holder.</p>
            {{ csrf_field() }}
            @if(Auth::user()->keys->count() == 1)
                <input type="hidden" name="key_id" value="{{ Auth::user()->keys->first()->key }}">
            @else
                <select>
                    @foreach(Auth::user()->keys as $key)
                        <option value="{{ $key->key }}">{{ $key->key }}</option>
                    @endforeach
                </select>
            @endif
            <input type="hidden" name="hash_id" value="{{ $fileset->hash_id }}">
            <input type="hidden" name="access_type" value="requested">
            <label>Message to the Copyright Holder: <textarea name="access_notes">{{ old('access_notes') }}</textarea></label>
            <input type="submit" class="button" value="Contact">
        </form>
    @else
        <p>Access Pending</p>
    @endif

@endsection