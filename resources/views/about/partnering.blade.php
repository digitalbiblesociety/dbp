@extends('layouts.app')

@section('head')


@endsection

@section('content')

    @include('layouts.partials.banner', [
        'title' => 'Joining as an Organization',
        'breadcrumbs' => [
            '/'                 => 'Home',
            '/about'            => 'Wiki',
            '/about/partnering' => 'Partnering'
        ]
    ])

    <div class="container">

                    <article class="media box">
                        <figure class="media-left">
                            <p class="image is-64x64 mt20">
                                <svg viewBox="0 0 256 310" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid">
                                    <path fill="#c36" d="M21 54L0 64v181l21 10V54" />
                                    <path fill="#a26" d="M131 229L21 255V54l110 25v150" />
                                    <path fill="#c36" d="M81 188l47 6v-1l1-77h-1l-47 6v66" />
                                    <path fill="#c36" d="M128 229l107 26h1V54h-1L128 80v149" />
                                    <path fill="#a26" d="M175 188l-47 6v-78l47 6v66" />
                                    <path fill="#a26" d="M175 90l-47 8-47-8 47-13 47 13" />
                                    <path fill="#a26" d="M175 220l-47-9-47 9 47 13 47-13" />
                                    <path fill="#c36" d="M81 90l47-12V0L81 23v67" />
                                    <path fill="#a26" d="M175 90l-47-12V0l47 23v67" />
                                    <path fill="#c36" d="M128 309l-47-23v-66l47 11 1 1-1 76v1" />
                                    <path fill="#a26" d="M128 309l47-23v-66l-47 11v78M235 54l21 10v181l-21 10V54" />
                                </svg>
                            </p>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                                <h3>The architecture of the DBP is founded on principles of partnership</h3>
                                <p>
                                    The API uses <a href="https://aws.amazon.com/s3/">Amazon S3</a> as it's content delivery tool and
                                    we've structured the API to be able to easily pull from a pool of s3 buckets. If you have content
                                    in S3 or are interested in distributing your content via the DBP please get in touch with us.
                                </p>
                                <a class="button" href="{{ route('contact.create') }}">Get in touch</a>
                            </div>
                        </div>
                    </article>

    </div>

@endsection