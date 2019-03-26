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
            {{--
            <div class="divMobile" id="mobileTrello">
                <a class="trello" target="_blank" href="https://trello.com/b/sfKX349T/tauribay"></a>
            </div>
            <div class="divMobile" id="mobileChangelog">
                <a class="changelog" target="_blank" href="https://github.com/bliser47/tauribay/commits/master"></a>
            </div>
            --}}
            <a class="navbar-brand" href="/">{{ env("APP_NAME") }}</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">

                <li class="dropdown {{ Request::segment(1) == 'trade' ? 'active' : '' }}">
                    <a href="/hirdetesek" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ __("Hirdetések") }}<span class="caret"></span></a>
                    <ul class="dropdown-menu trade-types-dropdown-menu">
                        <li><a href="/trade/char">{{ __("Karakter") }}</a></li>
                        <li><a href="/trade/raid">Raid</a></li>
                        <li><a href="/trade/credit">{{ __("Kredit") }}</a></li>
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
                    <li><a href="{{URL::to('/logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="glyphicon glyphicon-log-out"></span>{{__("Log out")}}</a></li>
                @endif
                {{--
                <li class="listDesktop">
                    <a class="changelog" target="_blank" href="https://github.com/bliser47/tauribay/commits/master"></a>
                </li>
                <li class="listDesktop">
                    <a class="trello" target="_blank" href="https://trello.com/b/sfKX349T/tauribay"></a>
                </li>
                --}}
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
<div class="amazon">
    <div class="container">
        <div id="MEIGitqPUFDH">
            {{ __("Honlapunkat az online hirdetések megjelenítése teszi lehetővé látogatóinknak.") }}
            <br/>
            {{ __("Kérjük, fontolja meg, hogy támogat minket a hirdetésblokkoló letiltásával.") }}
        </div>
        <iframe class="divDesktop" src="https://rcm-eu.amazon-adsystem.com/e/cm?o=2&p=48&l=ez&f=ifr&linkID=927efe9b2c16b88194199e1585a10d82&t=stamas47-21&tracking_id=stamas47-21" width="728" height="90" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0"></iframe>
        <iframe class="divMobile" src="https://rcm-eu.amazon-adsystem.com/e/cm?o=2&p=288&l=ez&f=ifr&linkID=66f2d6d8bad428fb96c56970c2ea5d57&t=stamas47-21&tracking_id=stamas47-21" width="320" height="50" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0"></iframe>
    </div>
</div>
