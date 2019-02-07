@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', [
        'title'    => 'Access Groups',
        'subtitle' => '',
        'breadcrumbs' => [
            route('dashboard')             => 'Dashboard',
            route('dashboard.keys.create') => 'Keys',
            '#'                            => 'Key Access Groups'
        ]
    ])

    <section class="container">
        <div class="columns is-mobile is-centered">
            <div class="box column is-8 has-text-centered">
                @foreach($key->access as $access)
                    <div class="column is-4-desktop is-12-mobile level-item has-text-centered">
                        <p class="heading">Enabled</p>
                        <p class="title is-size-5">{{ str_replace('_', ' ', $access->name) }}</p>
                    </div>
                @endforeach

                    <p class="is-size-4">If you delete your key any access controls associated with it will be lost.</p><br>
                    <p class="is-size-5 has-text-grey">The Current Access Controls are:</p><br>
                    <nav class="level columns is-multiline">

                    </nav>
                <form action="{{ route('dashboard.keys.destroy', ['id' => $key->id]) }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="key">
                    <button class="button is-primary" type="submit" href="{{ route('dashboard.keys.delete', ['id' => $key->id]) }}">Yes I want to delete my key</button>
                </form>

            </div>
        </div>
    </section>

@endsection