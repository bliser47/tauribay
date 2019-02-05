@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12 blog-main">
            <div class="well nomargin">
                <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="img/cms/news/mobile.jpg" alt="Mobil first">
                    </a>
                    <div class="media-body">
                        <div class="media-header">
                            <h3 class="media-heading"> {{ __("Mobile & Trello") }} </h3>
                            <p class="text-left text-italic">
                                {{ __("írta") }} <span class="warlock">Bliser</span> 2018.12.09
                            </p>
                        </div>
                    </div>
                    <div class="media-content">
                        <p>
                            {{ __("Ezentúl nem csak Githubon hanem már Trellon is követni tudjátok az oldal fejlesztését itt:") }}
                            <a href="https://trello.com/b/sfKX349T/tauribay">{{__("TauriBay Trello")}}</a>
                            {{ __(", vagy az új zöld kis ikon segítségével az oldal tetején.") }}
                        </p>
                        <p>
                            {{ __("Végre megcsináltam a Kari és Raid hirdetés oldalt mobilra, nem kell többet vele szenvedni a kis képernyőkön! Az optimalizálások sikeresen enyhítettek a szerveren de a mögötte lévő Cache rendszeren van mit még csíszolnom. Egyébként rengeteg ötletem van még hátra és ezek mind a Trello falra kerülnek majd fel!") }}
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
                        <img class="media-object" src="img/cms/news/fix_ladder.jpg" alt="PVE Ladder">
                    </a>
                    <div class="media-body">
                        <div class="media-header">
                            <h3 class="media-heading"> {{ __("Újítások") }} </h3>
                            <p class="text-left text-italic">
                                {{ __("írta") }} <span class="warlock">Bliser</span> 2019.01.28
                            </p>
                        </div>
                    </div>
                    <div class="media-content">
                        <p class="nomargin">
                        <p>
                            {{ __("Az útóbbi hónapban egyre többen látogatjátok az oldalt és hetente ugyancsak növekszik a raidekről való adatmennyiség. Ennek én csak örülni tudok de a webszerver nem :D.") }}
                        </p>
                        <p>
                            {{ __("Emiatt az utóbbi hónapban a PVE ladder optimalizálásával foglalkoztam nagyrészt és átrendeztem a keresési rendszer működését is. Remélem sokkal könnyebb és kellemesebb lesz haszálni majd!") }}
                        </p>
                        <p>
                            {{ __("Egyéb hírekben: Chris megírta a supportot, hogy WoDról és Evermoonról is érkezzenek trade/world/global üzenetek így most a Hirdetések oldalon onnan jövő hirdetéseket is találtok majd. Valamint sikerült visszaraknom a Raid hirdetés keresőt, de evel még sok sok munka van még hátra!") }}
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
@stop