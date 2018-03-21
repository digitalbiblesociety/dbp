@extends('layouts.app')

@section('head')
    <style>
        h2 {
            text-shadow: 2px 2px #ff0000;
        }
    </style>
@endsection

@section('content')

    <nav>

    </nav>

    <main>

        @foreach($articles as $article)
        <article>
            <header>
                <h2>{{ $article->title }} <small>{{ $article->subtitle }}</small></h2>
                @foreach($article->tags as $tag) <span>{{ $tag }}</span> @endforeach
            </header>
            <section></section>
            <footer></footer>
        </article>
        @endforeach
    </main>

@endsection