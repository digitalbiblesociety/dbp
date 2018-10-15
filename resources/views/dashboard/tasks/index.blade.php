@extends('layouts.app')

@section('head')
    <style>
        .task-wrap {
            background: #fff;
        }

    </style>
@endsection

@section('content')

    @include('layouts.partials.banner', [
        'title'     => 'Tasks',
        'subtitle'  => ''
    ])

    <div class="container">
        <section class="box task-wrap">

            <nav class="level">

                @foreach($counts as $title => $value)
                    <div class="level-item has-text-centered">
                        <div>
                            <p class="heading">{{ $title }}</p>
                            <p class="title">{{ $value }}</p>
                        </div>
                    </div>
                @endforeach

            </nav>

        </section>

        <section id="task-list">
            <task-bible-equivalents></task-bible-equivalents>

            <tabs animation="slide" :only-fade="false">
                <tab-pane label="{{ trans('dashboard.metadata') }}">

                </tab-pane>
                <tab-pane label="{{ trans('dashboard.links') }}">

                </tab-pane>
            </tabs>


        </section>

    </div>



@endsection