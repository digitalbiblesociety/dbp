<header class="cd-morph-dropdown">
    <a href="#0" class="nav-trigger">Open Nav<span aria-hidden="true"></span></a>

    <nav class="main-nav">
        <ul>
            {{--<li class="has-dropdown gallery" data-content="about"><a href="#0">About</a></li> --}}
            <li class="has-dropdown links" data-content="pricing"><a href="#0">Reference</a></li>
            @if(!Auth::user())
                <li class="has-dropdown button" data-content="login"><a href="#0">Login/Signup</a></li>
            @else
                <li class="has-dropdown button" data-content="user"><a href="#0">Home</a></li>
            @endif
        </ul>
    </nav>

    <div class="morph-dropdown-wrapper">
        <div class="dropdown-list">
            <ul>
                <li id="about" class="dropdown gallery">
                    <a href="#0" class="label">About</a>
                    <div class="content">
                        <ul>
                            <li><a href="#0"><svg class="icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/icons/icons-menu.svg#code-editor"></use></svg><em>Getting Started</em><span>A quick start guide for web developers</span></a></li>
                            <li><a href="#0"><em>What & Why</em><span>A brief description of the Koinos Project</span></a></li>
                            <li><a href="#0"><em>Title here</em><span>A brief description here</span></a></li>
                            <li><a href="#0"><em>Title here</em><span>A brief description here</span></a></li>
                        </ul>
                    </div>
                </li>

                <li id="pricing" class="dropdown links">
                    <a href="#0" class="label">Reference</a>
                    <div class="content">
                        <ul>
                            <li>
                                <h2>Data-Sets</h2>
                                <ul class="links-list">
                                    <li><a href="{{ route('view_bibles.index') }}">Bibles</a></li>
                                    <li><a href="{{ route('view_languages.index') }}">Languages</a></li>
                                    <li><a href="{{ route('view_alphabets.index') }}">Alphabets</a></li>
                                    <li><a href="{{ route('view_countries.index') }}">Countries</a></li>
                                    <li><a href="{{ route('view_numbers.index') }}">Numeral Sets</a></li>
                                    <li><a href="{{ route('view_organizations.index') }}">Organizations</a></li>
                                </ul>
                            </li>

                            <li>
                                <h2>Koinos</h2>
                                <ul class="links-list">
                                    <li><a href="{{ route('swagger_v4') }}">V4 Documentation</a></li>
                                    <li><a href="{{ route('swagger_v2') }}">V2 Documentation</a></li>
                                    <li><a href="{{ route('docs.sdk') }}">Examples and SDKs</a></li>
                                    <li><a href="{{ route('view_articles.index') }}">News & Changelog</a></li>
                                    <li><a href="#0">Organizational Membership</a></li>
                                    <li><a href="#0">About The Project</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </li>
                @if(!Auth::user())
                <li id="login" class="dropdown button">
                    <a href="#0" class="label">Contact</a>
                    <div class="content">
                        <a class="btn" href="/login">Login</a>
                        <a class="btn" href="/register">Signup</a>
                    </div>
                </li>
                @else
                    <li id="user" class="dropdown button">
                        <a href="#0" class="label">Contact</a>
                        <div class="content">
                            <ul class="links-list">
                                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">Logout</a>
                                <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form></li>
                                <li><a href="">Analytics</a></li>
                                <li><a href="">Roles</a></li>
                                <li><a href="/dashboard"><b>Dashboard</b></a></li>
                            </ul>
                        </div>
                    </li>
                @endif
            </ul>

            <div class="bg-layer" aria-hidden="true"></div>
        </div> <!-- dropdown-list -->
    </div> <!-- morph-dropdown-wrapper -->
</header>