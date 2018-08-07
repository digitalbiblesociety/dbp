<nav class="navbar ">
    <div class="navbar-brand">
        <a class="navbar-item" href="{{ url('/') }}">{!! config('app.name', trans('titles.app')) !!}</a>
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

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link" href="{{ route('docs') }}">Docs</a>
                <div class="navbar-dropdown">
                    <a class="navbar-item" href="{{ route('swagger_v4') }}"><p><strong>v4 API Docs</strong><br><small>OAS Specification & Inspector</small></p></a>
                    <a class="navbar-item" href="{{ route('swagger_v2') }}">v2 API Routes</a>
                    <a class="navbar-item" href="{{ route('docs.sdk') }}">SDK & Examples</a>
                    <a class="navbar-item" href="https://github.com/digitalbiblesociety/dbp/issues">Issues & Feedback</a>
                    <hr class="navbar-divider">
                    <div class="navbar-item">
                        <div>
                            <p class="is-size-6-desktop"><strong class="has-text-info">0.5.1</strong></p>
                            <small><a class="bd-view-all-versions" href="https://github.com/digitalbiblesociety/dbp/releases">View all versions</a></small>
                        </div>
                    </div>
                </div>
            </div>


            @role('admin')
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link" href="{{ route('public.home') }}">{!! trans('titles.adminDropdownNav') !!}</a>
                <div class="navbar-dropdown">
                    <a class="navbar-item" href="{{ url('/users') }}">@lang('titles.adminUserList')</a>
                    <a class="navbar-item" href="{{ url('/users/create') }}">@lang('titles.adminNewUser')</a>
                    <a class="navbar-item" href="{{ url('/themes') }}">@lang('titles.adminThemesList')</a>
                    <a class="navbar-item" href="{{ url('/logs') }}">@lang('titles.adminLogs')</a>
                    <a class="navbar-item" href="{{ url('/activity') }}">@lang('titles.adminActivity')</a>
                    <a class="navbar-item" href="{{ url('/phpinfo') }}">@lang('titles.adminPHP')</a>
                    <a class="navbar-item" href="{{ url('/routes') }}">@lang('titles.adminRoutes')</a>
                    <a class="navbar-item" href="{{ url('/active-users') }}">@lang('titles.activeUsers')</a>
                </div>
            </div>
            @endrole
            {{--
            <div class="navbar-item has-dropdown is-hoverable is-mega">
                <div class="navbar-link">Blog</div>
                <div id="blogDropdown" class="navbar-dropdown " data-style="width: 18rem;">
                    <div class="container is-fluid">
                        <div class="columns">
                            <div class="column">
                                <h1 class="title is-6 is-mega-menu-title">Sub Menu Title</h1>
                                <a class="navbar-item" href="/2017/08/03/list-of-tags/">
                                    <div class="navbar-content">
                                        <p>
                                            <small class="has-text-info">03 Aug 2017</small>
                                        </p>
                                        <p>New feature: list of tags</p>
                                    </div></a>
                                <a class="navbar-item" href="/2017/08/03/list-of-tags/">
                                    <div class="navbar-content">
                                        <p>
                                            <small class="has-text-info">03 Aug 2017</small>
                                        </p>
                                        <p>New feature: list of tags</p>
                                    </div></a>
                                <a class="navbar-item" href="/2017/08/03/list-of-tags/">
                                    <div class="navbar-content">
                                        <p>
                                            <small class="has-text-info">03 Aug 2017</small>
                                        </p>
                                        <p>New feature: list of tags</p>
                                    </div></a>
                            </div>
                            <div class="column">
                                <h1 class="title is-6 is-mega-menu-title">Sub Menu Title</h1>
                                <a class="navbar-item" href="/2017/08/03/list-of-tags/">
                                    <div class="navbar-content">

                                        <p>
                                            <small class="has-text-info">03 Aug 2017</small>
                                        </p>
                                        <p>New feature: list of tags</p>
                                    </div></a>
                                <a class="navbar-item" href="/documentation/overview/start/">
                                    Overview</a>
                                <a class="navbar-item" href="http://bulma.io/documentation/modifiers/syntax/">
                                    Modifiers</a>
                                <a class="navbar-item" href="http://bulma.io/documentation/columns/basics/">
                                    Columns</a>
                            </div>
                            <div class="column">
                                <h1 class="title is-6 is-mega-menu-title">Sub Menu Title</h1>
                                <a class="navbar-item" href="/2017/08/03/list-of-tags/">
                                    <div class="navbar-content">
                                        <p>
                                            <small class="has-text-info">03 Aug 2017</small>
                                        </p>
                                        <p>New feature: list of tags</p>
                                    </div></a>
                                <a class="navbar-item" href="/2017/08/03/list-of-tags/">
                                    <div class="navbar-content">
                                        <p>
                                            <small class="has-text-info">03 Aug 2017</small>
                                        </p>
                                        <p>New feature: list of tags</p>
                                    </div></a>
                                <a class="navbar-item" href="/2017/08/03/list-of-tags/">
                                    <div class="navbar-content">
                                        <p>
                                            <small class="has-text-info">03 Aug 2017</small>
                                        </p>
                                        <p>New feature: list of tags</p>
                                    </div></a>

                            </div>
                            <div class="column">
                                <h1 class="title is-6 is-mega-menu-title">Sub Menu Title</h1>
                                <a class="navbar-item" href="/documentation/overview/start/">
                                    Overview</a>
                                <a class="navbar-item" href="http://bulma.io/documentation/modifiers/syntax/">
                                    Modifiers</a>
                                <a class="navbar-item" href="http://bulma.io/documentation/columns/basics/">
                                    Columns</a>
                                <a class="navbar-item" href="http://bulma.io/documentation/layout/container/">
                                    Layout</a>
                            </div>
                        </div>
                    </div>

                    <hr class="navbar-divider">
                    <div class="navbar-item">
                        <div class="navbar-content">
                            <div class="level is-mobile">
                                <div class="level-left">
                                    <div class="level-item">
                                        <strong>Stay up to date!</strong>
                                    </div>
                                </div>
                                <div class="level-right">
                                    <div class="level-item">
                                        <a class="button bd-is-rss is-small" href="http://bulma.io/atom.xml">
                      <span class="icon is-small">
                        <i class="fa fa-rss"></i>
                      </span>
                                            <span>Subscribe</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="navbar-item has-dropdown is-hoverable">
                <div class="navbar-link">
                    More
                </div>
                <div id="moreDropdown" class="navbar-dropdown ">
                    <a class="navbar-item" href="http://bulma.io/extensions/">
                        <div class="level is-mobile">
                            <div class="level-left">
                                <div class="level-item">
                                    <p>
                                        <strong>Extensions</strong>
                                        <br>
                                        <small>Side projects to enhance Bulma</small>
                                    </p>
                                </div>
                            </div>
                            <div class="level-right">
                                <div class="level-item">
                  <span class="icon has-text-info">
                    <i class="fa fa-plug"></i>
                  </span>
                                </div>
                            </div>
                        </div></a>
                </div>
            </div>

            <a class="navbar-item" href="http://bulma.io/expo/"><span class="bd-emoji">🎨</span> &nbsp;Expo</a>
            <a class="navbar-item" href="http://bulma.io/love/"><span class="bd-emoji">❤️</span> &nbsp;Love</a>
            --}}
        </div>

        <div class="navbar-end">
            <div class="navbar-item">
                 @guest <a class="button is-primary" href="{{ route('login') }}">Login</a> @else
                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link" href="#">
                            @if((Auth::User()->profile) && Auth::user()->profile->avatar_status == 1)
                                <img src="{{ Auth::user()->profile->avatar }}" alt="{{ Auth::user()->name }}" class="user-avatar-nav">
                            @endif
                            {{ Auth::user()->name }}
                        </a>
                        <div class="navbar-dropdown">
                            <a class="navbar-item" href="{{ url('/profile/'.Auth::user()->name) }}">@lang('titles.profile')</a>
                            <a class="navbar-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> {{ __('Logout') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"> @csrf </form>
                        </div>
                    </div>
                 @endguest
                </div>
            </div>
        </div>
    </div>
</nav>