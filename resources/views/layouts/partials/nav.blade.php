<nav class="navbar ">
    <div class="navbar-brand">
        <a class="navbar-item" href="{{ url('/') }}">{!! config('app.name', trans('app.app')) !!}</a>
        <a class="navbar-item is-hidden-desktop" href="https://github.com/digitalbiblesociety/dbp" target="_blank"><span class="icon" style="color: #333;"><i class="fa fa-github"></i></span></a>
        <a class="navbar-item is-hidden-desktop" href="https://twitter.com/dbp" target="_blank"><span class="icon" style="color: #55acee;"><i class="fa fa-twitter"></i></span></a>

        <div class="navbar-burger burger" data-target="navMenubd-example">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <div id="navMenubd-example" class="navbar-menu">
        <div class="navbar-start">
            <div class="navbar-item has-dropdown is-hoverable is-mega">
                <div class="navbar-link">API</div>
                <div id="blogDropdown" class="navbar-dropdown " data-style="width: 18rem;">
                    <div class="container is-fluid">
                        <div class="columns">

                            <div class="column">
                                <h1 class="title is-6 is-mega-menu-title">Guides</h1>
                                <a class="navbar-item" href="{{ route('docs.getting_started') }}">{{ trans('about.getting_started') }}</a>
                            </div>

                            <div class="column">
                                <h1 class="title is-6 is-mega-menu-title">API Reference</h1>
                                <a class="navbar-item" href="{{ route('swagger_v4') }}">{{ trans('about.api_v4_title') }}</a>
                                <a class="navbar-item" href="{{ route('swagger_v2') }}">{{ trans('about.api_v2_title') }}</a>
                            </div>

                            <div class="column">
                                <h1 class="title is-6 is-mega-menu-title">Resources</h1>
                                <a disabled class="navbar-item has-text-grey-light">Demos (Coming Soon)</a>
                                <a class="navbar-item" href="{{ route('docs.sdk') }}">{{ trans('about.sdk_title') }}</a>
                                <a class="navbar-item" href="https://github.com/digitalbiblesociety/dbp/issues">{{ trans('about.issues_title') }}</a>
                                <a class="navbar-item" href="{{ route('contact.create') }}">{{ trans('about.contact_title') }}</a>
                            </div>

                            <div class="column">
                                <h1 class="title is-6 is-mega-menu-title">Legal</h1>
                                <a class="navbar-item" href="{{ route('license') }}">License</a>
                                <a class="navbar-item" href="{{ route('privacy_policy') }}">Privacy Policy</a>
                                <a class="navbar-item has-text-grey-light" href="#">Text Copyrights</a>
                                <a class="navbar-item" href="{{ route('eula') }}">End-user license agreement</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link" href="#">About</a>
                <div class="navbar-dropdown">
                    <a class="navbar-item has-text-grey-light" disabled>Who are we</a>
                    <a class="navbar-item has-text-grey-light" disabled>Why build this</a>
                    <a class="navbar-item" href="{{ route('about.join') }}">Joining as a User</a>
                    <a class="navbar-item" href="{{ route('about.partnering') }}">Partnering as an Organization</a>
                    <a class="navbar-item has-text-grey-light" disabled>How DBP relates to...</a> {{-- route('relations') --}}
                </div>
            </div>
            <a class="navbar-item" href="{{ route('wiki_bibles.all') }}">Bibles</a>
            <a class="navbar-item has-text-grey-light" disabled>Resources</a>

            @role('admin')
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link" href="{{ route('public.home') }}">{!! trans('app.adminDropdownNav') !!}</a>
                <div class="navbar-dropdown">
                    <a class="navbar-item" href="{{ url('/users') }}">@lang('app.adminUserList')</a>
                    <a class="navbar-item" href="{{ url('/users/create') }}">@lang('app.adminNewUser')</a>
                    <a class="navbar-item" href="{{ url('/logs') }}">@lang('app.adminLogs')</a>
                    <a class="navbar-item" href="{{ url('/activity') }}">@lang('app.adminActivity')</a>
                    <a class="navbar-item" href="{{ url('/php-info') }}">@lang('app.adminPHP')</a>
                    <a class="navbar-item" href="{{ url('/routes') }}">@lang('app.adminRoutes')</a>
                    <a class="navbar-item" href="{{ url('/active-users') }}">@lang('app.activeUsers')</a>
                </div>
            </div>
            @endrole
        </div>

        <div class="navbar-end">

            <div id="translation-dropdown" class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                    <svg class="panel-icon icon"><use xlink:href="/images/icons.svg#translate"></use></svg>
                </a>
                <div class="navbar-dropdown">
                    @foreach(Localization::getLocales() as $localeCode => $properties)
                        <a class="navbar-item" rel="alternate" hreflang="{{ $localeCode }}" href="{{ Localization::getLocaleUrl($localeCode, true) }}">{{ $properties['native'] }} </a>
                    @endforeach
                </div>
            </div>
    </div>
</nav>