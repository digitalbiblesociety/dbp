@extends('layouts.app')

@section('content')

    <section id="unity">
        <div class="row">
            <div class="medium-6 columns">
                <h2 class="text-center">The Unity Tables</h2>
                <p>There are a lot of fantastic Bible APIs out there. The Digital Bible Library does a great job of managing copyrights, the Global Bible Catalogue's collection is huge, Bible Search is elegant, the The Digital Bible Platform does a great job of delivering audio, and the collection of content from Find a Bible is truly impressive (a little biased on that last one).</p>
                <p>Each is exceptional, highly specialized, and useful in its own way. Bible Developers will sometimes themselves working across six or more disparate APIs. Many of those APIs deliver, in their own way, the same content that led to duplicated or redundant records. To resolve this we've partnered with the find.bible project to create the Koinos Equivalency Service.</p>
                <p>The find.bible project been working tirelessly for years to collect and consolidate information about the different Bible APIs of the world. Now, for the first time, these equivalent IDs are open for everyone to use. Query Koinos once, and be connected to some of the best Bible APIs out there.</p>
            </div>
            <div class="medium-6 columns">
                <img src="https://placehold.it/700x400" />
            </div>
        </div>

        <div class="row">
            <div class="medium-4 columns">
                <h5>{{ trans('docs.bibles_show_title') }}</h5>
                <code>{{ route('api_bibles.equivalents', 'ENGKJV') }}</code>

                <code><pre><?php
	                $arrContextOptions=array(
		                "ssl"=>array(
			                "verify_peer"=>false,
			                "verify_peer_name"=>false,
		                ),
	                );
	                echo json_decode(json_encode(file_get_contents( route('api_bibles.equivalents', 'ENGKJV'), false, stream_context_create($arrContextOptions))), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?>
                </pre></code>

            </div>
        </div>

    </section>

@endsection