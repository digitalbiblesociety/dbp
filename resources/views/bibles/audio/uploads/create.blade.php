@extends('layouts.app')

@section('head')
    <title>Audio Uploader | Koinos</title>
@endsection

@section('content')

    <form class="row" action="/bibles/audio/uploads" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="medium-6 columns">
            <input type="text" name="bible_id">
        </div>
        <div class="medium-6 columns">
            <input type="file" name="bible_zip">
        </div>
        <div class="medium-3 columns centered">
            <input class="button" type="submit">
        </div>
    </form>

@endsection