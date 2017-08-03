<nav role="navigation">
    <ul>
        <li><a href="/" class="logo">Ko<strong>in</strong>os</a></li>
        <li><a href="/bibles/audio/uploads/">Uploader</a></li>
        <li><a href="/books/">Books</a></li>
        @if(!Auth::user())
            <li><a href="/login">Login or Signup</a></li>
        @else
            <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
        @endif
    </ul>
</nav>