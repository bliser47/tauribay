<div class="encounter-form-body">
    @if ( $mapId == 1098 )
        <div class="bossNameImg divDesktop" style="background-image:url('{{ URL::asset("img/maps/" . $mapId . ".jpg") }}')">
            <img src="{{ URL::asset("img/encounters/" . $encounterId . ".png") }}" alt="{{ \TauriBay\Encounter::getName($encounterId)  }}"/>
            {{ \TauriBay\Encounter::getName($encounterId) }} - {{ \TauriBay\Encounter::SIZE_AND_DIFFICULTY[$difficultyId] }}
        </div>
        <div class="bossNameImgMobile divMobile" style="background-image:url('{{ URL::asset("img/maps/" . $mapId . ".jpg") }}')">
            <img src="{{ URL::asset("img/encounters/" . $encounterId . ".png") }}" alt="{{ \TauriBay\Encounter::getName($encounterId)  }}"/>
            {{ \TauriBay\Encounter::getNameShort($encounterId)  }} - {{ \TauriBay\Encounter::SIZE_AND_DIFFICULTY_SHORT[$difficultyId] }}
        </div>
    @else
        <div class="bossName divDesktop">
            {{ \TauriBay\Encounter::getName($encounterId) }}  - {{ \TauriBay\Encounter::SIZE_AND_DIFFICULTY[$difficultyId] }}
        </div>
        <div class="bossName divMobile">
            {{ \TauriBay\Encounter::getNameShort($encounterId) }}  - {{ \TauriBay\Encounter::SIZE_AND_DIFFICULTY_SHORT[$difficultyId] }}
        </div>
    @endif
    <div class="encounter-body">
        <div class="panel-heading nopadding" role="tab" id="headingTwo">
            <h4 class="panel-title">
                <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion2" href="#encounterFilter" aria-expanded="false" aria-controls="encounterFilter">
                    {{ __("Szűrés") }}
                </a>
            </h4>
        </div>
        <div id="encounterFilter" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
            <div class="panel-body">
                {!! Form::open(array("method" => "get","id"=>"encounter-form")) !!}
                <div class="form-group col-md-4">
                    <legend> {{ __("Nehézség") }} </legend>
                    <div id="expansions-container" class="input-group col-md-12">
                        {!! Form::select('difficulty_id', $difficulties, Input::get('difficulty_id', $difficultyId), ['required', 'id' => 'expansion', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz nehézséget")]); !!}
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <legend> {{ __("Realm") }} </legend>
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
                <div class="form-group col-md-4">
                    <legend> {{ __("Frakció") }} </legend>
                    <div class="input-group">
                        <div class="checkbox checkbox-inline checkbox-alliance checkbox-white-tick checkbox-faction">
                            {!! Form::checkbox('alliance',2,Input::get('alliance'),array("id"=>"faction-alliance","class"=>"faction")) !!}
                            <label for="faction-alliance"> Alliance </label>
                        </div>
                        <div class="checkbox checkbox-inline checkbox-horde checkbox-white-tick checkbox-faction">
                            {!! Form::checkbox('horde',1,Input::get('horde'),array("id"=>"faction-horde","class"=>"faction")) !!}
                            <label for="faction-horde"> Horde </label>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12 nomargin col-sm-nopadding">
                    <button class="btn btn-block btn-success" name="filter" value="1" type="submit">
                        {{ __("Szűrés") }}
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<div id="encounter-form-response">
    <ul class="nav nav-tabs" role="tablist">
        @foreach ( $modes as $modeKey => $modeName )
            <li id="modePanel{{ $modeKey  }}" data-mode="{{ $modeKey }}" class="modePanel home-main-tab {{ $modeKey == $modeId ? "active" : "" }}" role="presentation"><a href="#mode-{{ $modeKey }}" aria-controls="mode-{{ $modeKey }}"  role="tab" data-toggle="tab">{{ $modeName }}</a></li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach ( $modes as $modeKey => $modeName )
            <div role="tabpanel" class="tab-pane {{  $modeKey == $modeId ? "active" : "" }}" id="mode-{{ $modeKey}}">
                <div class="encounters_loading"><div class="loader" style="display:block"></div></div>
                <div data-mode="{{ $modeKey }}" class="encounter-mode-loading-container"></div>
            </div>
        @endforeach
    </div>
</div>