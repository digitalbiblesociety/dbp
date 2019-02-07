@extends('layouts.app')

@section('head')
    <style>
        .card {
            margin-bottom:15px;
        }


        .notice {
            width: 300px;
            margin: 15px auto;
            font-family: Monaco, monospace;
            font-size: 10.2px;
            text-align: justify;
        }

        .card-footer-item {
            text-align: center;
            box-sizing: border-box;
            font-size:medium;
        }

        .card-footer-item form {
            width:100%;
            height:100%;
            display: block;
        }

        .card-footer-item button {
            padding: 0;
            border: none;
            background: none;
            font-weight: inherit;
            font-size: inherit;
            color: #643c64;
            cursor: pointer;
            width:100%;
            height:100%;
            display: block;
        }

        .card-footer-item button:hover,
        .card-footer-item:hover {
            background-image: linear-gradient(141deg, #ad2462 0%, #c83c64 71%, #d34a5b 100%);
            color:#FFF!important;
        }

    </style>
@endsection

@section('content')

    @include('layouts.partials.banner', [
        'title' => 'Create key for ' . Auth::user()->name ?? 'user'
    ])
    <div class="container">
        <div class="columns">
            <form class="column is-6" action="{{ route('dashboard.keys.store') }}" method="POST">
                {{ csrf_field() }}
                <label class="label">{{ trans('fields.name') }}
                    <input class="input" type="text" name="name">
                </label>
                <label class="label">{{ trans('fields.description') }}
                    <textarea class="textarea" name="description"></textarea>
                </label>
                <input class="button is-primary" type="submit" value="{{ trans('fields.submit_key') }}" />
            </form>
            <div class="column is-6">
                @foreach(Auth::user()->keys as $key)
                    <div class="card">
                        <header class="card-header">
                            <p class="card-header-title">{{ $key->name }}</p>
                            @if(isset($key->created_at))
                               <time class="card-header-icon has-text-grey has-text-right" datetime="{{ $key->created_at }}">
                                   {{ $key->created_at->diffForHumans() }}
                               </time>
                            @endif
                        </header>
                        <div class="card-content">
                            <div class="content"><pre>{{ $key->key }}</pre>{{ $key->description }}</div>
                        </div>
                        <footer class="card-footer">
                            <div class="card-footer-item">
                                <form action="{{ route('dashboard.keys.clone', ['id' => $key->id]) }}" method="Post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="key">
                                    <button type="submit" href="{{ route('dashboard.keys.clone', ['id' => $key->id]) }}">Regenerate</button>
                                </form>
                            </div>
                            <a href="{{ route('dashboard.keys.access', ['id' => $key->id]) }}" class="card-footer-item">Access Controls</a>
                            <a href="{{ route('dashboard.keys.delete', ['id' => $key->id]) }}" class="card-footer-item">Delete</a>
                        </footer>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="container">
        <div class="notice">
            Important to note is that all keys have disparate
            bible access controls, for any new key to inherit
            access controls clone the api key from its parent
        </div>
    </div>

@endsection