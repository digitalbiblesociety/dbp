@extends('layouts.app')

@section('template_title')
    Welcome {{ Auth::user()->name }}
@endsection

@section('head')
@endsection

@section('content')

    @include('layouts.partials.banner', [
        'title'     => 'Welcome '. Auth::user()->name,
        'subtitle'  => 'Admin Access'
    ])
    <div class="container">
        {{--
        <div class="field is-grouped is-grouped-multiline columns">
            @level(1)
            <div class="control column">
                <div class="tags has-addons">
                    <span class="tag is-dark">Level 1</span>
                    <span class="tag is-info">User Notes and & Highlights</span>
                </div>
            </div>
            @endlevel

            @level(2)
            <div class="control column">
                <div class="tags has-addons">
                    <span class="tag is-dark">Level 2</span>
                    <span class="tag is-info">User Beta Program</span>
                </div>
            </div>
            @endlevel

            @level(3)
            <div class="control column">
                <div class="tags has-addons">
                    <span class="tag is-dark">Level 3</span>
                    <span class="tag is-info">Content Owner / Contributor</span>
                </div>
            </div>
            @endlevel

            @level(4)
            <div class="control column">
                <div class="tags has-addons">
                    <span class="tag is-dark">Level 4</span>
                    <span class="tag is-info">Content Owner / Contributor</span>
                </div>
            </div>
            @endlevel

            @level(5)
            <div class="control column">
                <div class="tags has-addons">
                    <span class="tag is-dark">Level 5</span>
                    <span class="tag is-info">Archivist Level</span>
                </div>
            </div>
            @endlevel
        </div>
        --}}
    <div class="columns">
        <div class="column">
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">Bibles</p>
            <a href="#" class="card-header-icon" aria-label="more options">Options</a>
        </header>
        <div class="card-content">
            <div class="content">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus nec iaculis mauris.
                <a href="#">@bulmaio</a>. <a href="#">#css</a> <a href="#">#responsive</a>
                <br>
                <time datetime="2016-1-1">11:09 PM - 1 Jan 2016</time>
            </div>
        </div>
        <footer class="card-footer">
            <a href="{{ route('dashboard.bibles') }}" class="card-footer-item">All</a>
            <a href="{{ route('dashboard.bibles') }}" class="card-footer-item">Create</a>
            <div class="card-footer-item"><input type="text"></div>
        </footer>
    </div>
        </div>

        <div class="column">
        <div class="card">
            <header class="card-header">
                <p class="card-header-title">Filesets</p>
                <a href="#" class="card-header-icon" aria-label="more options">Options</a>
            </header>
            <div class="card-content">
                <div class="content">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus nec iaculis mauris.
                    <a href="#">@bulmaio</a>. <a href="#">#css</a> <a href="#">#responsive</a>
                    <br>
                    <time datetime="2016-1-1">11:09 PM - 1 Jan 2016</time>
                </div>
            </div>
            <footer class="card-footer">
                <a href="{{ route('dashboard.bibles') }}" class="card-footer-item">All</a>
                <a href="{{ route('dashboard.bibles') }}" class="card-footer-item">Create</a>
                <div class="card-footer-item"><input type="text"></div>
            </footer>
        </div>
        </div>

    </div>


            @role('admin')

            <hr>

            <p>
                You have permissions:
                @permission('view.users')
                <span class="badge badge-primary margin-half margin-left-0">
                        {{ trans('permsandroles.permissionView') }}
                    </span>
                @endpermission

                @permission('create.users')
                <span class="badge badge-info margin-half margin-left-0">
                        {{ trans('permsandroles.permissionCreate') }}
                    </span>
                @endpermission

                @permission('edit.users')
                <span class="badge badge-warning margin-half margin-left-0">
                        {{ trans('permsandroles.permissionEdit') }}
                    </span>
                @endpermission

                @permission('delete.users')
                <span class="badge badge-danger margin-half margin-left-0">
                        {{ trans('permsandroles.permissionDelete') }}
                    </span>
                @endpermission

            </p>

            @endrole

        </div>
    </div>
    </div>

@endsection
