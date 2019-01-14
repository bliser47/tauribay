@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12 blog-main">
            <div class="well nomargin">
                <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="img/cms/news/dps_rankings.jpg" alt="Új encounter adatok">
                    </a>
                    <div class="media-body">
                        <div class="media-header">
                            <h3 class="media-heading"> {{ __("Fight adatok") }} </h3>
                            <p class="text-left text-italic">
                                {{ __("írta") }} <span class="warlock">Bliser</span> 2019.01.14
                            </p>
                        </div>
                    </div>
                    <div class="media-content">
                        <p>
                            {{ __("Sikeresen újraírtam a rendszer bizonyos részeit, így most már több adatra lehet rákeresni fightonként!") }}
                        </p>
                        <ul>
                            <li> {{ __("Az új PVE ladder oldal:") }}  <a href="{{ URL::to("/ladder/pve/tot/mop/") }}">{{ __("ITT") }}</a> </li>
                            <li> {{ __("A legjobb DPS-ek pld Durumu-n") }} <a href="{{ URL::to("/ladder/pve/mop/tot/durumu/10hc") }}">{{ __("ITT") }}</a> </li>
                        </ul>
                        <p class="nomargin">
                            {{ __("Legközelebb még több feature várható!") }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 blog-main">
            <div class="well nomargin">
                <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="img/cms/news/progress_adatok.jpg" alt="Új progress adatok">
                    </a>
                    <div class="media-body">
                        <div class="media-header">
                            <h3 class="media-heading"> {{ __("Progress oldal") }} </h3>
                            <p class="text-left text-italic">
                                {{ __("írta") }} <span class="warlock">Bliser</span> 2018.12.09
                            </p>
                        </div>
                    </div>
                    <div class="media-content">
                        <p>
                            {{ __("Örömmel jelentem be, hogy a progress oldal első rész kész van!") }}
                        </p>
                        <ul>
                            <li> {{ __("A szerver összes guildjének ToT (HC) progressje:") }}  <a href="{{ URL::to("/progress/guild") }}">{{ __("ITT") }}</a> </li>
                            <li> {{ __("A content legjobb boss kill idői:") }} <a href="{{ URL::to("/progress/kills") }}">{{ __("ITT") }}</a> </li>
                        </ul>
                        <p class="nomargin">
                            {{ __("Hamarosan a progress oldal sokkal több statisztikával bővűl!") }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 blog-main">
            <div class="well nomargin">
                <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="img/cms/news/ujitas_az_oldalon.jpg" alt="Újítások az oldalon">
                    </a>
                    <div class="media-body">
                        <div class="media-header">
                            <h3 class="media-heading"> {{ __("Újítások") }} </h3>
                            <p class="text-left text-italic">
                                {{ __("írta") }} <span class="warlock">Bliser</span> 2018.08.10
                            </p>
                        </div>
                    </div>
                    <div class="media-content">
                        <p>
                            {{ __("Újra nekiláttam az oldal fejlesztésének. Legútóbbi újítások:") }}
                        </p>
                        <ul class="nomargin">
                            <li> {{ __("Új zöld design") }} </li>
                            <li> {{ __("ItemLevel oldal frissítés") }} </li>
                            <li> {{ __("Aug. 30") }} {{ __(": Manuális iLvl frissítés!") }} </li>
                            <li> <b> {{ __("Szept. 6") }} </b> {{ __(": Manuális iLvl frissítés fixed") }} </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 blog-main">
            <div class="well nomargin">
                <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="img/cms/news/udovozollek_a_tauri_bayen.jpg" alt="Üdvözöllek a TauriBayen">
                    </a>
                    <div class="media-body">
                        <div class="media-header">
                            <h3 class="media-heading"> {{ __("Üdvözöllek a TauriBayen") }} </h3>
                            <p class="text-left text-italic">
                                {{ __("írta") }} <span class="warlock">Bliser</span> 2018.02.09
                            </p>
                        </div>
                    </div>
                    <div class="media-content">
                        <p class="nomargin">
                            {{ __("Sziasztok! Végre újra fut az oldal és egyenlőre a karakter hirdetésekben tudtok keresgélni. Az elkövetkező hetekben megírom a GDKP, a Kredit és a Guild hirdetések felismerését is amit a kézzel felvihető hirdetések lehetősége követ a regisztrált felhasználóknak.") }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop