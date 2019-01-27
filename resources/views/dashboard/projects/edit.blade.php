@extends('layouts.app')

@section('content')

    <div class="container">

        <section class="box">

            <form action="{{ route('dashboard.projects.update', ['project_id' => $project->id]) }}">
                {{ csrf_field() }}
                <lable>
                    <input type="text" name="name" value="{{ $project->name ?? old('name') }}" />
                </lable>

                <lable>
                    <input type="text" name="url_site" value="{{ $project->url_site ?? old('url_site') }}" />
                </lable>

                <lable>
                    <input type="file" name="url_avatar" />
                </lable>

                <lable>
                    <input type="file" name="url_avatar_icon" />
                </lable>

                <textarea name="description">
                     {{ $project->description ?? old('description') }}
                </textarea>

            </form>

        </section>

    </div>

@endsection