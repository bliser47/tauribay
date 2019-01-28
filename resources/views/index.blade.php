@extends('layouts.default')
@section('content')
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
                            <li> {{ __("A szerver összes guildjének ToT (HC) progressje:") }}  <a href="{{ URL::to("/progress") }}">{{ __("ITT") }}</a> </li>
                            <li> {{ __("A content legjobb boss kill idői:") }} <a href="{{ URL::to("/ladder/pve/tot") }}">{{ __("ITT") }}</a> </li>
                        </ul>
                        <p class="nomargin">
                            {{ __("Hamarosan a progress oldal sokkal több statisztikával bővűl!") }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop