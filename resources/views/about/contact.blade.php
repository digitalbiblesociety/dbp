@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', [
        'title' => trans('about.contact_title'),
        'breadcrumbs' => [
            '/'     => trans('about.home'),
            '#'     => trans('about.contact_title')
        ]
    ])

    <div class="container">

        <form class="box columns is-multiline" action="{{ route('contact.store') }}" method="POST">
            {{ csrf_field() }}
            <div class="column is-12">
                <h2 class="has-text-centered is-size-4">{{ trans('about.contact_form_title') }}</h2>
            </div>

            <div class="column is-6">
                <div class="field">
                    <label class="label">{{ trans('about.contact_form_email') }}</label>
                    <div class="control"><input class="input" type="text" name="email"></div>
                </div>

                <div class="columns">
                    <div class="field column is-6">
                        <label class="label">{{ trans('about.contact_form_subject') }}</label>
                        <div class="control"><input class="input" type="text" name="subject"></div>
                    </div>
                    <div class="field column is-6">
                        <label class="label">{{ trans('about.contact_form_inquiry') }}</label>
                        <div class="control">
                            <div class="select">
                                <select name="purpose">
                                    <option>{{ trans('about.contact_form_purpose_general') }}</option>
                                    <option>{{ trans('about.contact_form_purpose_bug') }}</option>
                                    <option>{{ trans('about.contact_form_purpose_help') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="column is-6">
                <div class="field">
                    <label class="label">Message</label>
                    <div class="control"><textarea class="textarea" name="message"></textarea></div>
                </div>
                <div class="control">
                    <button class="button is-primary">Submit</button>
                </div>
            </div>

        </form>

        <nav class="level is-mobile">
            <div class="level-left is-size-7">
                {{-- TODO: Correct Phone Number --}}
                <span><b>Phone:</b> 1-000-000-000 </span>
                <span><b>Email:</b> &#105;&#110;&#102;&#111;&#64;&#100;&#98;&#112;&#52;&#46;&#111;&#114;&#103; </span>
            </div>
        </nav>


    </div>

@endsection

@section('footer')
    {!! app('captcha')->render('en'); !!}
@endsection