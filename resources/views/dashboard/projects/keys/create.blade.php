@extends('layouts.app')

@section('body')

    @include('layouts.partials.banner', [
        'title'     => 'Request API key access',
        'subtitle'  => 'Admin Access'
    ])

    <section class="container">
        <div class="box">
            <form>
                <label>Project Name
                    <select name="project_id">
                        @foreach($user->projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </label>
            </form>
        </div>
    </section>

@endsection