<div class="title-bar" data-responsive-toggle="responsive-menu" data-hide-for="medium">
    <button class="menu-icon" type="button" data-toggle="responsive-menu"></button>
    <div class="title-bar-title">Menu</div>
</div>
<div class="top-bar" id="responsive-menu">
    <div class="top-bar-left">
        <ul class="menu">
            <li class="menu-text">koinos</li>
        </ul>
    </div>
    <div class="top-bar-right">
        <ul class="menu">
            <li><a href="{{ route('view_bibles.index') }}">Bibles</a></li>
            <li><a href="{{ route('view_books.index') }}">Books</a></li>
            @if(!Auth::user())
                <li><a href="/login">Login or Signup</a></li>
            @else
                <li><a href="/home">Dashboard</a></li>
                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                <li>
                    <a href="#">Create New...</a>
                    <ul class="vertical menu">
                        <li><a href="{{ route('view_bibles.create') }}">Bible</a></li>
                        <li><a href="/bibles/connections/create">Bible Connections <small>Connect HTML, MP3, MP4</small></a></li>
                        <li><a href="{{ route('view_resources.create') }}">Resource</a></li>
                        <li><a href="{{ route('view_languages.create') }}">Language</a></li>
                        <li><a href="{{ route('view_alphabets.create') }}">Alphabet</a></li>
                        <li><a href="{{ route('view_numbers.create') }}">Numeral Set</a></li>
                    </ul>
                </li>
            @endif
            <li><input type="search" id="site-search" placeholder="Search The Site"></li>
        </ul>
    </div>
</div>