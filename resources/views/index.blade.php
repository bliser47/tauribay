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
                        <p>{{ __("Az interjút elolvashatjátok: ") }}<a href="https://tauribay.hu/interview">{{ __("ITT") }}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop