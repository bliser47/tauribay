@if ( $expansionId == 4 )
    <div class="bossName" style="background-image:url('{{ URL::asset("img/maps/" . $mapId . ".jpg") }}')">
@else
    <div class="bossName" style="background-image:url('{{ URL::asset("img/maps/default.jpg") }}')">
@endif
    <span> {{ \TauriBay\Encounter::getMapName($expansionId, $mapId) }}</span>
</div>
{!! Form::open(array("method" => "get","id"=>"ladder-filter-form")) !!}
    <div class="col-md-6 col-xs-12">
        <div class="input-group">
            <div class="checkbox checkbox-inline checkbox-realm">
                {!! Form::checkbox('tauri',1,Input::get('tauri'),array("id"=>"realm-tauri","class"=>"realm")) !!}
                <label for="realm-tauri"> Tauri </label>
            </div>
            <div class="checkbox checkbox-inline checkbox-realm">
                {!! Form::checkbox('wod',1,Input::get('wod'),array("id"=>"realm-wod","class"=>"realm")) !!}
                <label for="realm-wod"> WoD </label>
            </div>
            <div class="checkbox checkbox-inline checkbox-realm">
                {!! Form::checkbox('evermoon',1,Input::get('evermoon'),array("id"=>"realm-evermoon","class"=>"realm")) !!}
                <label for="realm-evermoon"> Evermoon </label>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xs-12">
        <div class="input-group">
            <div class="checkbox checkbox-inline checkbox-alliance checkbox-white-tick checkbox-faction">
                {!! Form::checkbox('alliance',1,Input::get('alliance'),array("id"=>"faction-alliance","class"=>"faction")) !!}
                <label for="faction-alliance"> Alliance </label>
            </div>
            <div class="checkbox checkbox-inline checkbox-horde checkbox-white-tick checkbox-faction">
                {!! Form::checkbox('horde',1,Input::get('horde'),array("id"=>"faction-horde","class"=>"faction")) !!}
                <label for="faction-horde"> Horde </label>
            </div>
        </div>
    </div>
{!! Form::close() !!}
<ul class="nav nav-tabs" role="tablist">
    @foreach ( $difficulties as $index => $difficulty )
        <li id="difficultyPanel{{ $index }}" data-mode="{{ $index }}" data-url="{{ URL::to("/ladder/pve/" . \TauriBay\Encounter::EXPANSION_SHORTS[$expansionId] ."/" . \TauriBay\Encounter::getMapUrl($expansionId, $mapId) . "/" . \TauriBay\Encounter::SIZE_AND_DIFFICULTY_URL[$difficulty["id"]]) }}" class="map-difficulty-tab divDesktop home-main-tab {{ $index == $defaultDifficultyIndex ? "active" : "unLoaded" }}" role="presentation"><a href="#difficulty-{{ $index }}" aria-controls="difficulty-{{ $index}}" role="tab" data-toggle="tab">{{ $difficulty["name"] }}</a></li>
        <li id="difficultyPanel{{ $index }}" data-mode="{{ $index }}" data-url="{{ URL::to("/ladder/pve/" . \TauriBay\Encounter::EXPANSION_SHORTS[$expansionId] ."/" . \TauriBay\Encounter::getMapUrl($expansionId, $mapId) . "/" . \TauriBay\Encounter::SIZE_AND_DIFFICULTY_URL[$difficulty["id"]]) }}" class="map-difficulty-tab divMobile home-main-tab {{ $index == $defaultDifficultyIndex ? "active" : "unLoaded" }}" role="presentation"><a href="#difficulty-{{ $index }}" aria-controls="difficulty-{{ $index}}" role="tab" data-toggle="tab">{{ \TauriBay\Encounter::SIZE_AND_DIFFICULTY_SHORT[$difficulty["id"]] }}</a></li>
    @endforeach
</ul>
<div class="tab-content">
    @foreach ( $difficulties as $index => $difficulty )
        <div data-mode="{{ $index }}" role="tabpanel" class="map-difficulty tab-pane {{ $index == $defaultDifficultyIndex ? "active" : "" }}" id="difficulty-{{ $index }}">
            <div data-difficulty="{{  $difficulty["id"] }}" class="encounters_loading ajax-map-difficulty"><div class="loader" style="display:block"></div></div>
        </div>
    @endforeach
</div>