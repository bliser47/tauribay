@extends('guides.guide')
@section('guide_content')
    <div class="panel panel-default nomargin">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active" role="presentation"><a href="#intro" aria-controls="character" role="tab" data-toggle="tab"></i> {{__("Intro")}}</a></li>
            <li role="presentation"><a href="#talents" aria-controls="character" role="tab" data-toggle="tab"><img class="spell_icon" src="http://mop-static.tauri.hu/images/icons/medium/ability_marksmanship.png"/> {{__("Talents")}}</a></li>
            <li role="presentation"><a href="#glyphs" aria-controls="character" role="tab" data-toggle="tab"><img class="spell_icon" src="http://mop-static.tauri.hu/images/icons/medium//inv_inscription_tradeskill01.png"/> {{__("Glyphs")}}</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="active tab-pane" id="intro">
                <div class="panel-body">
                    @yield('class_guide_intro')
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="talents">
                @include('guides.class.talents')
            </div>
            <div role="tabpanel" class="tab-pane" id="glyphs">
                @include('guides.class.glyphs')
            </div>
        </div>
    </div>
@stop