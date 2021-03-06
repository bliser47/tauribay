<div class="encounter-form-body">
    @if ( $expansionId == 4 )
        <div class="bossNameImg divDesktop" style="background-image:url('{{ URL::asset("img/maps/" . $mapId . ".jpg") }}')">
            <img src="{{ URL::asset("img/encounters/" . $encounterId . ".png") }}" alt="{{ \TauriBay\Encounter::getName($encounterId)  }}"/>
            {{ \TauriBay\Encounter::getName($encounterId) }} - {{ \TauriBay\Encounter::SIZE_AND_DIFFICULTY[$difficultyId] }}
        </div>
        <div class="bossNameImgMobile divMobile" style="background-image:url('{{ URL::asset("img/maps/" . $mapId . ".jpg") }}')">
            <img src="{{ URL::asset("img/encounters/" . $encounterId . ".png") }}" alt="{{ \TauriBay\Encounter::getName($encounterId)  }}"/>
            {{ \TauriBay\Encounter::getNameShort($encounterId)  }} - {{ \TauriBay\Encounter::SIZE_AND_DIFFICULTY_SHORT[$difficultyId] }}
        </div>
    @else
        <div class="bossName divDesktop" style="background-image:url('{{ URL::asset("img/maps/default.jpg") }}')">
            {{ \TauriBay\Encounter::getName($encounterId) }} - {{ \TauriBay\Encounter::SIZE_AND_DIFFICULTY[$difficultyId] }}
        </div>
        <div class="bossName divMobile" style="background-image:url('{{ URL::asset("img/maps/default.jpg") }}')">
            {{ \TauriBay\Encounter::getNameShort($encounterId) }} - {{ \TauriBay\Encounter::SIZE_AND_DIFFICULTY_SHORT[$difficultyId] }}
        </div>
    @endif
    <div class="encounter-body">
        {!! Form::open(array("method" => "get","id"=>"encounter-form")) !!}
        <input type="hidden" name="expansion_id" value="{{ $expansionId }}"/>
        <input type="hidden" name="map_id" value="{{ $mapId }}"/>
        <input type="hidden" name="role" value="{{ Input::get('role') }}"/>
        <input type="hidden" name="class" value="{{ Input::get('class') }}"/>
        <input type="hidden" name="spec" value="{{ Input::get('spec') }}"/>
        <div class="col-md-3 col-xs-6">
            <div id="expansions-container" class="input-group col-md-12">
                {!! Form::select('encounter_id', $encounters, Input::get('encounter_id', $encounterId), ['required', 'id' => 'expansion', 'class' => "control selectpicker input-large"]); !!}
            </div>
        </div>
        <div class="col-md-2 col-xs-3">
            <div id="expansions-container" class="input-group col-md-12">
                {!! Form::select('difficulty_id', $difficulties, Input::get('difficulty_id', $difficultyId), ['required', 'id' => 'expansion', 'class' => "control selectpicker input-large"]); !!}
            </div>
        </div>
        <div class="col-md-1 col-xs-3 encounter-sub-filter-maxplayers">
            <div id="expansions-container" class="input-group col-md-12">
                <input type="number" placeholder="#Player" id="max_players" class="form-control" name="max_players" value="{!! Input::get('max_players') !!}">
            </div>
        </div>
        <div class="col-md-3 col-xs-12 pd-top-7 encounter-sub-filter-realm">
            <div class="input-group">
                <div class="checkbox checkbox-inline checkbox-realm">
                    {!! Form::checkbox('tauri',2,Input::get('tauri'),array("id"=>"realm-tauri","class"=>"realm")) !!}
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
        <div class="col-md-3 col-xs-12 pd-top-7 encounter-sub-filter-faction">
            <div class="input-group">
                <div class="checkbox checkbox-inline checkbox-alliance checkbox-white-tick checkbox-faction">
                    {!! Form::checkbox('alliance',\TauriBay\Faction::ALLIANCE,Input::get('alliance'),array("id"=>"faction-alliance","class"=>"faction")) !!}
                    <label for="faction-alliance"> Alliance </label>
                </div>
                <div class="checkbox checkbox-inline checkbox-horde checkbox-white-tick checkbox-faction">
                    {!! Form::checkbox('horde',\TauriBay\Faction::HORDE,Input::get('horde'),array("id"=>"faction-horde","class"=>"faction")) !!}
                    <label for="faction-horde"> Horde </label>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<div id="encounter-form-response">
    <ul class="nav nav-tabs" role="tablist">
        @foreach ( $modes as $modeKey => $modeName )
            <li id="modePanel{{ $modeKey  }}" data-mode="{{ $modeKey }}" class="modePanel home-main-tab {{ $modeKey == $modeId ? "active" : "" }}" role="presentation"><a href="#{{ $modeKey }}" aria-controls="{{ $modeKey }}"  role="tab" data-toggle="tab">{{ $modeName }}</a></li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach ( $modes as $modeKey => $modeName )
            <div data-mode="{{ $modeKey }}" role="tabpanel" class="tab-pane {{  $modeKey == $modeId ? "active" : "" }}" id="{{ $modeKey}}">
                <div class="encounters_loading"><div class="loader" style="display:block"></div></div>
                <div data-mode="{{ $modeKey }}" class="encounter-mode-loading-container"></div>
            </div>
        @endforeach
    </div>
</div>