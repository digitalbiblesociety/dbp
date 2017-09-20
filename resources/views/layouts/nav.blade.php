<nav role="navigation">
    <ul class="vertical medium-horizontal menu" data-responsive-menu="accordion medium-dropdown">
        <li><a href="/" class="logo">Ko<strong>in</strong>os</a></li>
        <li><a href="/books/">Books</a></li>
        @if(!Auth::user())
            <li><a href="/login">Login or Signup</a></li>
        @else
            <li><a href="/home">Dashboard</a></li>
            <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
        <li>
            <a href="#">Create New...</a>
            <ul class="vertical menu">
                <li><a href="/bibles/create">Bibles</a></li>
                <li><a href="/resources/create">Resources</a></li>
            </ul>
        </li>
        @endif
    </ul>
</nav>