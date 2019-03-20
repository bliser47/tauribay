@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12 blog-main col-sm-nopadding">
            <div class="well nomargin">
                <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="img/cms/news/server_fixes.jpg?v=1" alt="Server & Fixes">
                    </a>
                    <div class="media-body">
                        <div class="media-header">
                            <h3 class="media-heading"> {{ __("Új szerver és fixek") }} </h3>
                            <p class="text-left text-italic">
                                {{ __("írta") }} <span class="warlock">Bliser</span> 2019.03.20
                            </p>
                        </div>
                    </div>
                    <div class="media-content">
                        <p>
                            {{ __("Ez elmúlt hónapban nagyrészt inkább javításokat végeztem valamint optimalizáltam a kódot. De sikerült egyébként új feature-eket is berakni:") }}
                        </p>
                        <ul>
                            <li>{{ __("Az új PVE létra most már Realm és Frakció szinten menti el a leggyorsabb killeket és legnagyobb dps-eket.") }}</li>
                            <li>{{ __("Sikerült megoldani, hogy a random raideknek is legyen frakciója és most már nem csak a legjobb random raid kerül be leggyorsabb killek közé hanem a 3 realmről mindkét frakcióból a legjobb (tehát 6).") }}</li>
                            <li>{{ __("Valamint most már Normal nehézségű progress-re is lehet keresni a Guild oldalon: ") }}<a href="{{ URL::to("/progress?difficulty=normal") }}">{{ __("ITT") }}</a></li>
                            <li>{{ __("Továbbá a Score rendszer már nem foglalkozik a játékos iLvL-vel hanem egyszerűen, a specen belűl az adott bosson és nehézségen legjobb dps (dps-ek esetén), hps (healerek esetén) hasonlít.") }}</li>
                        </ul>
                        <p>
                            {{ __("Na de a legfontosabb hír, hogy a TauriBay most már Intel Xeon szerveren fut (eddig kis teljesítményű ARM proceszorokon ment). Ez összetéve a remek Cache rendszerrel, amit most már kézzel is lehet frissíteni (az a zöld kiss ikon a bal felső sarokban) szuper gyors válaszidőt biztosít.") }}
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
@stop