<nav class="navbar navbar-default">
    <div class="container">
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
                <li class="{{ Request::segment(1) == 'trade' ? 'active' : '' }}"><a href="/trade/char">{{ __("Hirdetések") }}</a></li>
                <li class="{{ Request::segment(1) == 'top' ? 'active' : '' }}"><a href="/top">{{ __("Toplista") }}</a></li>
                <li class="{{ Request::path() == 'progress/guild' ? 'active' : '' }}"><a href="/progress/guild">{{ __("Guild progress") }}</a></li>
                <li class="{{ Request::path() == 'progress/kills' ? 'active' : '' }}"><a href="/progress/kills">{{ __("PVE Ladder") }}</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li><a href="{{URL::to('/register')}}"><span class="glyphicon glyphicon-user"></span>{{ __("Regisztráció") }} </a></li>
                    <li><a href="{{URL::to('/login')}}"><span class="glyphicon glyphicon-log-in"></span>{{ __("Login") }} </a></li>
                @else
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                    <li><a href="{{URL::to('/home')}}"><span class="glyphicon glyphicon-user"></span>{{__("Profil")}}</a></li>
                    <li><a href="{{URL::to('/logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="glyphicon glyphicon-log-out"></span>{{__("Kijelentkezés")}}</a></li>
                @endif
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