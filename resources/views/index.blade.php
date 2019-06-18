@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12 blog-main col-sm-nopadding">
            <div class="well nomargin">
                <div class="media">
                    <div class="media-body">
                        <div class="media-header">
                            <h3 class="media-heading"> {{ __("Interjú Chrissel") }} </h3>
                            <p class="text-left text-italic">
                                {{ __("írta") }} <span class="warlock">Bliser</span> 2019.06.12
                            </p>
                        </div>
                    </div>
                    <div style="padding:0px 10px 0px 10px">
                        <p>{{ __("A múlt héten sikerült Chrisszel egy rövid interjút csinálni. Megpróbáltam érdekesebb kérdéseket feltenni olyan szerverrel kapcsolatos dolgokról amikről talán nem mindenki tud. Kellemes olvasást!") }}</p>
                        <ol>
                            <li><b>{{ __("Mi a cég filozófiája?") }}</b></li>
                            <p>{{ __("Szeretnénk maradandót alkotni. Ha valaki meghallja a Tauri szót, akkor tudja, hogy miről van szó, akár kellemes emlékek, akár rossz emlékek (De legyen inkább jobb). Tudok olyan baráti társaságokról, párokról, akik Taurin találkoztak.") }}</p>
                            <li><b>{{ __("Mi lenne ha veled bármi történne; ki örökli a Taurit?") }}</b></li>
                            <p>{{ __("JayD, ő vele kezdtem a taurit, és a cég is közös.") }}</p>
                            <li><b>{{ __("Mitől jobb a SmartScript mint a régi mód?") }}</b></li>
                            <p>{{ __("Ég és föld. A mostani scripteket a régi rendszerben megírni többszöröse lenne a mostaninál. Hatalmas előrelépés volt. A SmartScript teljesen célorientált rendszer, ami kifejezetten AI scriptelésre lett kitalálva. Egy hasonlat: “régi mód” - kiskanál, “SmartScript” - ásó.") }}</p>
                            <p>{{ __("A SmartAI használatával elsősorban időt spórolunk, régen amíg C++ ban történt a scriptelés, addig minden változtatás a kód újrafordítását és a szerver újraindítását vonta magával. SmartAI esetén egy gombnyomással újra tudjuk tölteni egy adott objektum scriptjét, és csak új AI feature bevezetése miatt kell esetlegesen a core-t újrafordítani.") }}</p>
                            <li><b>{{ __("Sokakban felmerül, hogy néha már meglévő/működő rendszereket SmartAIba írtok újra, “új kontent/bugok helyett”. Ez gondolom a jövőre való tekintettel van?") }}</b></li>
                            <p>{{ __("Ez hosszú távú befektetés, hisz utána sokkal könnyebb ezeknek a dolgoknak a karbantartása.") }}</p>
                            <li><b>{{ __("Szerinted mi az ami elválaszt a konkurenciától?") }}</b></li>
                            <p>{{ __("Egyértelmű, hogy a minőség. Odafigyelünk olyan részletekre, amire a konkurenciánál idő, vagy tudás hiányában nem tudnak. Pld hogy. az Ironforge székek jó irányba nézzenek ;)") }}</p>
                            <p>{{ __("Véleményem szerint érződik, hogy mi nem csak összedobunk valamit, hogy menjen, hanem törekszünk minőségi tartalmat nyújtani.") }}</p>
                            <li><b>{{ __("Mik azok a lépések amiket ti tesztek egy quest/boss/zóna scriptelésekor amit más szerverek kihagynak?") }}</b></li>
                            <p>{{ __("Más szerverek, mint régen mi is, legtöbbször azt csinálják, hogy nagyjából megcsinálják, hogy működjön pld. egy quest. Gondolok itt arra, hogy felveszem-leadom. Mi megpróbálunk arra törekedni, hogy a körítés is meglegyen. Magam is játszom taurin (játszottam eredetin wotlkban/catában/pandában/wodban/legionben is), és sokszor el is felejtem, hogy nem eredetin vagyok. Pont ez a célunk.") }}</p>
                            <li><b>{{ __("Hány játékost bírna el a szerver a jelenlegi infrastruktúrán?") }}</b></li>
                            <p>{{ __("Nem sokat, de maga a kérdés nem pontos ebben a formában. Sok mindentől függ, hogy mennyi játékost bír el egy szerver. Egy processzorban manapság már több mag van, ezt úgy lehet kihasználni, hogy több szálon adjuk a feladatokat neki egyszerre. Nálunk jelenleg a “map”-ok vannak különválasztva. Tehát pl Northrend és Kalimdor, vagy Pandaria más-más szálon fut, így amíg egy adott mag a processzorban nincs kihasználva, addig bírja a terhelést. Ezért volt az, hogy Pandaria nyitásnál Pandaria lagolt, Stormwindben vagy Orgrimmarban pedig semmi lagg nem volt.") }}</p>
                            <p>{{ __("Ha nincs sok játékos és mob egy helyen, akkor elég sok játékost képes kezelni egy map. WoTLK óta sajnos nagyon sok objektum lett egy pontban, így drasztikusan csökkent az egy szálra jutó játékosok maximális száma is.") }}</p>
                            <p>{{ __("A kérdésre tehát ebben a formában nem lehet válaszolni, mert, ha 100 ember játszana és mindenki Pandaria egy pontjában lenne, akkor kb 100 embert bírna el a szerver laggmentesen. Viszont ha mindenki szétszóródik a világban, instákban stb., akkor akár 4000-t is elbír. WotLK-val kb olyan 5000-6000 embert lehet kiszolgálni a mostani legerősebb gépeken. (Ha valaki más wow szervernél többet lát, az biztosan átverés.)") }}</p>
                            <p>{{ __("WotLK létszámára visszatérve, a legnagyobb változást a terhelésben Cataclysm fejlesztése hozta. Az egész világban mozognak az NPC-k, a mozgásuk kalkulációja pedig megterheli szintén a szervert. Ez mellé adódik az egyre növekvő világ, egyre több objektum kerül be, ami csak növeli a szerver terhelését. Ezért is célszerű a mapokat külön szálon kezelni.") }}</p>
                            <li><b>{{ __("Mennyire jelent ez gondot Timeless Isle valamint majd Legion bekerülésekor?") }}</b></li>
                            <p>{{ __("Pont ezért optimalizáljuk most a core-t. Nagyon sok időt szentelünk erre jelenleg. Főleg JayD foglalkozik most vele, de én is csinálgatom.") }}</p>
                            <li><b>{{ __("Hogy áll az Atlantisszal való kapcsolat?") }}</b></li>
                            <p>{{ __("Elég jól. Tartjuk velük a kapcsolatot folyamatosan, és segítünk egymásnak. Van egy nagyobb tervünk, ami nem publikus, és még kidolgozás alatt áll.") }}</p>
                            <li><b>{{ __("Mint tudni, Atlantiss remek stabilitás javításokat kapott a Tauritól; mik voltak azok a javítások amiket az Atlantisszal való kapcsolat miatt tudtatok végrehajtani?") }}</b></li>
                            <p>{{ __("Apróbb javításokat csináltunk csak, a legfőbb stabilitási hibát ők találták meg. “Invalid write” hibájuk volt. Sajnos azzal az a probléma, hogy teljesen máshol áll meg debuggerben is a kód, és nem tudod egyszerűen, hogy mi okozta. Erre valók a memória analizálást segítő programok (pl. Valgrind, Sanitizer), csak sajnos ezeknek hatalmas ára van, hisz nagy a CPU igényűk, így éles környezetben kb esélytelen használni. Viszont igazából az Atlantissnak segítésből mi profitáltunk végeredményében.") }}</p>
                            <p>{{ __("Anno néztük a Sanitizer nevezetű kiegészítőt a fordítóhoz, viszont nem tudott sok mindent. Ők nyitották fel a szemünket, hogy kb mindent tud szűrni már. Ennek hála PTR-t sokszor abban futtatjuk, így nagyon sok kódolási hibát már élesbe kerülés előtt kijavítunk. Azt a hibát is megtaláltuk, ami miatt instabil lett a szerver Firelands idejében :)))) Tehát mi mindenféleképpen jól jártunk, pedig nem is gondoltuk volna, mi csak segíteni akartunk nekik. :) Azóta is osztjuk meg egymással a tapasztalatainkat, ami szerintem egy nagyon jó dolog.") }}</p>
                            <li><b>{{ __("Ha visszanezel múlt évre, mi az amit drasztikusan változott a szerveren?") }}</b></li>
                            <p>{{ __("Igazából jelenleg azt érzem, hogy kicsit megrekedtünk, az elmúlt 1 évben előrelépést nem éreztünk, ezen igyekszünk változtatni. Talán annyi, hogy kicsit többet kommunikálunk a felhasználókkal.") }}</p>
                            <li><b>{{ __("És szerinted a játékosok erre hogyan válaszoltak, ők miben változtak az elmúlt 1 évben?") }}</b></li>
                            <p>{{ __("Szerintem frusztráltak, amit teljes mértékben megértek, és igyekszünk ezen változtatni.") }}</p>
                            <li><b>{{ __("Hol látod a szervert 1 év múlva?") }}</b></li>
                            <p>{{ __("A következő egy évben négy nagyobb project van tervben, ha ez mind jól sikerül, akkor elég jó helyen látom a szervert :) (új csili-vili launcher, wotlk, legion és az Atlantisszal a közös projectünk.)") }}</p>
                            <li><b>{{ __("Mi az amit másképp akartok csinálni a jövőben?") }}</b></li>
                            <p>{{ __("Igazából lehet, nem kellene minden apró eventet megírni, ezzel gyorsítva a munkánkat. Ezen akarunk változtatni. Ami csak 1-2 embernek tűnne fel, azt elsumákoljuk :)") }}</p>
                            <li><b>{{ __("Hogy áll a WotLK realm?") }}</b></li>
                            <p>{{ __("Kb 10%-on, SoO most mindenféleképpen a prioritás. Utána következik a WotLK realm, amit kb 1 hónap alatt tervezünk teljesen gatyába rázni :) Utána GO legion") }}</p>
                            <li><b>{{ __("Mi az ami új/más lesz a régi WotLK realmhez képest?") }}</b></li>
                            <p>{{ __("Hát a régi wotlk-ról szép emlékeim voltak, amíg be nem logoltam. Valljuk be őszintén, anno iszonyat rossz állapotban volt. Nagyon sok hibát találtunk és javítottuk az alap spell működésekben is. Tehát mondhatni ég és föld lesz a kettő.") }}</p>
                            <li><b>{{ __("Hogy áll Siege of Orgrimmar?") }}</b></li>
                            <p>{{ __("Ütemterv szerint a hónap végére elkészül teljesen tesztelhető állapotban.") }}</p>
                            <li><b>{{ __("Melyik boss volt a legnehezebb scriptelési szempontból; legkönnyebb valamint a fejlesztők kedvence?") }}</b></li>
                            <p>{{ __("Higinek Iron Juggernaut volt a legkönnyebb, Garrosh-t imádta legjobban és Galakras volt a legszörnyűbb boss.") }}</p>
                            <p>{{ __("Webby-t meginterjúvoltam én is: “Talán Spoils of Pandariát volt legjobb megírni, jó volt hozzá egy egységes rendszert felépíteni, és mikor ez meglett, egyszerre elkezdett működni minden, az nagyon jó volt, habár végére kicsit lapos lett. Legrosszabb Sha of Pride volt, mint fejlesztés nehézség és kedv tekintetében is, nagyon nem szabványos megoldásokat alkalmaztak nála, sok volt a felesleges bonyolítás. A legkönnyebb boss a végére maradt: Thok, egyszerű a működése, hozzáértőbb ember készítette el eredetileg is, habár emiatt unalmas is volt, és ehhez képest a tesztelése nehézkes kicsit.") }}”</p>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop