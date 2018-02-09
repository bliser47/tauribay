<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">TauriBay</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="{{ Request::path() == '/' ? 'active' : '' }}"><a href="/">Főoldal</a></li>
                <li class="dropdown {{ Request::segment(1) == 'hirdetesek' ? 'active' : '' }}">
                    <a href="/hirdetesek" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Hirdetések<span class="caret"></span></a>
                    <ul class="dropdown-menu trade-types-dropdown-menu">
                        <li><a href="/hirdetesek/karakter">Karakter</a></li>
                        <li class="disabled"><a>GDKP</a></li>
                        <li class="disabled"><a>Kredit</a></li>
                    </ul>
                </li>
                <li><a href="/changelog">Changelog</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li><a href="{{ url('/register') }}"><span class="glyphicon glyphicon-user"></span>Regisztráció</a></li>
                    <li><a href="{{ url('/login') }}"><span class="glyphicon glyphicon-log-in"></span>Bejelentkezés</a></li>
                @else
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                    <li><a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="glyphicon glyphicon-log-out"></span>Kijelentkezés</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>