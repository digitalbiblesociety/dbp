@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', [
        'title' => trans('about.relations_title'),
        'breadcrumbs' => [
            '/'     => trans('about.home'),
            '#'     => trans('about.relations_title')
        ]
    ])

    <div class="container box">
        <div class="columns is-multiline">
        <section class="column">
            <h3 class="is-size-5 has-text-centered">How does the DBP relate to the Digital Bible library:</h3>
            <p class="is-size-6 has-text-grey">
                The main purpose of the Digital Bible library is Rights management.
                The Digital Bible platform serves as a distribution mechanism for scriptures obtained via the digital Bible library and other means.
                The DBP collects and catalogs the IDs from the DBL so that developers can more quickly integrate the DBL with their own and other systems.
                <a class="button is-primary" href="{{ route('docs_bible_equivalents') }}">Read More about the Bible equivalent's project.</a>
            </p>
        </section>

        <section class="column">
            <h3 class="is-size-5 has-text-centered">How does the DBP relate to the Digital Bible Society:</h3>
            <p class="is-size-6 has-text-grey">The Digital Bible Society is the developer of DBP 4.0 in partnership with FCBH.</p>
        </section>

        <section class="column">
            <h3 class="is-size-5 has-text-centered">How does the DBP relate to bible.is:</h3>
            <p class="is-size-6 has-text-grey">Bible.is is the test instance for the DBP. Created by Faith comes by hearing and Digital Bible Society as an example of what the API could offer potential developers. Much of the sample code and the examples for react are pulled directly from bible.is.</p>
        </section>
        </div>
    </div>

@endsection