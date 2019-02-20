<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="divMobile" id="mobileLanguage">
                <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    {!! language()->flag()  !!}<span class="caret"></span>
                </a>
                <ul class="dropdown-menu trade-types-dropdown-menu">
                    {!! language()->flags() !!}
                </ul>
            </div>
            <div class="divMobile" id="mobileTrello">
                <a class="trello" target="_blank" href="https://trello.com/b/sfKX349T/tauribay"></a>
            </div>
            <div class="divMobile" id="mobileChangelog">
                <a class="changelog" target="_blank" href="https://github.com/bliser47/tauribay/commits/master"></a>
            </div>
            <a class="navbar-brand" href="/">TauriBay</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">

                <li class="dropdown {{ Request::segment(1) == 'trade' ? 'active' : '' }}">
                    <a href="/hirdetesek" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ __("Hirdetések") }}<span class="caret"></span></a>
                    <ul class="dropdown-menu trade-types-dropdown-menu">
                        <li><a href="/trade/char">{{ __("Karakter") }}</a></li>
                        <li><a href="/trade/raid">Raid</a></li>
                    </ul>
                </li>
                <li class="{{ Request::segment(1) == 'top' ? 'active' : '' }}"><a href="/top">{{ __("Toplista") }}</a></li>
                <li class="{{ Request::segment(1) == 'progress' ? 'active' : '' }}"><a href="/progress">{{ __("Guilds") }}</a></li>
                <li class="{{ Request::segment(1) == 'ladder' ? 'active' : '' }}"><a href="/ladder/pve/mop/tot">{{ __("PVE Ladder") }}</a></li>
                <li class="{{ Request::segment(1) == 'player' ? 'active' : '' }}"><a href="/player">{{ __("Player") }}</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li><a href="{{URL::to('/register')}}"><span class="glyphicon glyphicon-user"></span>{{ __("Reg.") }} </a></li>
                    <li><a href="{{URL::to('/login')}}"><span class="glyphicon glyphicon-log-in"></span>{{ __("Login") }} </a></li>
                @else
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                    <li><a href="{{URL::to('/home')}}"><span class="glyphicon glyphicon-user"></span>{{__("Profil")}}</a></li>
                    <li><a href="{{URL::to('/logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="glyphicon glyphicon-log-out"></span>{{__("Kijelentkezés")}}</a></li>
                @endif
                <li class="listDesktop">
                    <a class="changelog" target="_blank" href="https://github.com/bliser47/tauribay/commits/master"></a>
                </li>
                <li class="listDesktop">
                    <a class="trello" target="_blank" href="https://trello.com/b/sfKX349T/tauribay"></a>
                </li>
                <li class="dropdown listDesktop">
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