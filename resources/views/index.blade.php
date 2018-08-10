@extends('layouts.default')
@section('content')
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
                            <p>
                                {{ __("Újra nekiláttam az oldal fejlesztésének. Legútóbbi újítások:") }}
                            </p>
                        </div>
                        <ul>
                            <li> {{ __("Új zöld design") }} </li>
                            <li> {{ __("Összes hirdetés (utolsó 1 óra) feature") }} </li>
                            <li> {{ __("ItemLevel oldal frissítés") }} </li>
                            <li> {{ __("Időzóna javítás") }} </li>
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
                        <p>
                            {{ __("Sziasztok! Végre újra fut az oldal és egyenlőre a karakter hirdetésekben tudtok keresgélni. Az elkövetkező hetekben megírom a GDKP, a Kredit és a Guild hirdetések felismerését is amit a kézzel felvihető hirdetések lehetősége követ a regisztrált felhasználóknak.") }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop