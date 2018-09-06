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
                <li class="{{ Request::path() == '/' ? 'active' : '' }}">
                    <a href="/">
                        {{ __("Főoldal") }}
                    </a>
                </li>
                <li class="dropdown {{ Request::segment(1) == 'trade' ? 'active' : '' }}">
                    <a href="/trade" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        {{ __("Hirdetések") }}
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu trade-types-dropdown-menu">
                        {{-- <li><a href="/trade">{{ __("Minden") }}</a></li> --}}
                        <li><a href="/trade/char">{{ __("Karakter") }}</a></li>
                        <li class="disabled"><a>GDKP</a></li>
                        <li class="disabled"><a>{{ __("Kredit") }}</a></li>
                    </ul>
                </li>
                <li><a href="/ilvl">iLvL Toplist</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                {{--
                @if (Auth::guest())
                    <li><a href="{{ url('/register') }}"><span class="glyphicon glyphicon-user"></span>{{ __("Regisztráció") }} </a></li>
                    <li><a href="{{ url('/login') }}"><span class="glyphicon glyphicon-log-in"></span>{{ __("Bejelentkezés") }} </a></li>
                @else
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                    <li><a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="glyphicon glyphicon-log-out"></span>Kijelentkezés</a></li>
                @endif
                --}}
                <li><a class="changelog" href="https://github.com/bliser47/tauribay/commits/master"></a></li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        {!! language()->flag()  !!}<span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu trade-types-dropdown-menu">
                        {!! language()->flags() !!}
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>