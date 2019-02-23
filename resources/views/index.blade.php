@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12 blog-main col-sm-nopadding">
            <div class="well nomargin">
                <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="img/cms/news/dps_loot.jpg" alt="Loot & Score">
                    </a>
                    <div class="media-body">
                        <div class="media-header">
                            <h3 class="media-heading"> {{ __("Loot & Score") }} </h3>
                            <p class="text-left text-italic">
                                {{ __("írta") }} <span class="warlock">Bliser</span> 2019.02.21
                            </p>
                        </div>
                    </div>
                    <div class="media-content">
                        <p>
                            {{ __("Végre rávettem magam pár hete, hogy elmentsem a boss fightok alatt kiesett lootot. Most már láthatjátok a DPS és Heal melletti tabban mit dobott az adott boss. Valamint a boss oldalon, a Loot tabban láthatjátok az itemeket kiesésének esélyét.") }}
                        </p>
                        <p>
                            {{ __("A player oldalon már nagyon rég óta gondolkodtam és az is már használható de a visszamenőleges adatoknak kelleni fog 1-2 hét míg megjelennek. Na de, mi az a DPS és HPS score és hogy kerűl kiszámításra?") }}
                        </p>
                        <p> {{ __("Minden fight után a játékos DPS és HPSje összehasonlítódik a legjobb 5 ugyanazon class és spec-en lévő játékosok DPS/HPS-eikhez, akik -4 és +4 item level-en belűl vannak a játékos gearjéhez képest. Ha valaki 100% alatt van akkor azt jelenti, hogy átlagoson alúlteljesít ha pedig fölötte akkor a 100an felűli érték jelenti a túlteljesítményt!") }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 blog-main col-sm-nopadding">
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
                        <p>
                            {{ __("P.S. Lehet szűrni a Guild oldalkon belűl!") }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 blog-main col-sm-nopadding">
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
@stop