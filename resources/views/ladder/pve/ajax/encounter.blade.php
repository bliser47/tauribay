@if ( $mapId == 1098 )
    <div class="bossNameImg" style="background-image:url('{{ URL::asset("img/maps/" . $mapId . ".jpg") }}')">
        <img src="{{ URL::asset("img/encounters/" . $encounterId . ".png") }}" alt=""/>
        {{ \TauriBay\Encounter::getName($encounterId)  }}
    </div>
@else
    <div class="bossName">
        {{ \TauriBay\Encounter::getName($encounterId)  }}
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
            <div class="form-group col-md-6">
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
            <div class="form-group col-md-6">
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
            <div class="form-group col-sm-4 col-md-3 col-sm-nopadding">
                <legend> {{ __("Nehézség") }} </legend>
                <div id="expansions-container" class="input-group col-md-12">
                    {!! Form::select('difficulty_id', $difficulties, Input::get('difficulty_id', $difficultyId), ['required', 'id' => 'expansion', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz nehézséget")]); !!}
                </div>
            </div>
            <div class="form-group col-sm-4 col-md-3 col-sm-nopadding">
                <legend> {{ __("Category") }} </legend>
                <div id="category-container" class="input-group col-md-12">
                    {!! Form::select('sorting_id', $sorting,  Input::get('sorting_id', $sortingId), ['required', 'id' => 'category', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz kategóriát")]); !!}
                </div>
            </div>
            <div class="form-group col-sm-4 col-md-3 col-sm-nopadding">
                <legend> {{ __("Kaszt") }} </legend>
                <div id="class-container" class="input-group col-md-12">
                    {!! Form::select('class_id', $classes,  Input::get('class_id', $classId), ['required', 'id' => 'class', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz kasztot")]); !!}
                </div>
            </div>
            <div class="form-group col-sm-4 col-md-3 col-sm-nopadding">
                <legend> {{ __("Spec") }} </legend>
                <div id="spec-container" class="input-group col-md-12">
                    {!! Form::select('spec_id', $specs,  Input::get('spec_id', $specId), ['required', 'id' => 'spec', 'class' => "disabled control selectpicker input-large", 'placeholder' =>  __("Válassz spec-et")]); !!}
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
<div id="encounter-form-response">
    <div class="encounters_loading"><div class="loader" style="display:block"></div></div>
    <div class="encounter-loading-container"></div>
</div>

{{--
<ul class="nav nav-tabs" role="tablist">
    @foreach ( $modes as $modeKey => $mode )
        <li id="modePanel{{ $modeKey  }}" data-mode="{{ $modeKey }}" class="modePanel home-main-tab {{ $modeKey == "dps" ? "active" : "" }}" role="presentation"><a href="#mode-{{ $modeKey }}" aria-controls="mode-{{ $modeKey }}"  role="tab" data-toggle="tab">{{ $mode }}</a></li>
    @endforeach
</ul>
<div class="tab-content">
    @foreach ( $modes as $modeKey => $mode )
        <div role="tabpanel" class="tab-pane {{ $modeKey == "dps" ? "active" : "" }}" id="mode-{{ $modeKey }}">
            <ul class="nav nav-tabs" role="tablist">
                @foreach ( $difficulties as $index => $difficulty )
                    <li id="difficultyPanel{{$difficulty["id"]  }}" data-difficulty="{{ $difficulty["id"] }}" data-mode="{{ $modeKey }}" class="difficultyPanel divDesktop home-main-tab {{ $index == $defaultDifficultyIndex ? "active" : "" }}" role="presentation"><a href="#difficulty-{{ $difficulty["id"] }}" aria-controls="difficulty-{{ $difficulty["id"] }}"  role="tab" data-toggle="tab">{{ $difficulty["name"] }}</a></li>
                    <li id="difficultyPanel{{$difficulty["id"]  }}" data-difficulty="{{ $difficulty["id"] }}" data-mode="{{ $modeKey }}" class="difficultyPanel divMobile home-main-tab {{ $index == $defaultDifficultyIndex ? "active" : "" }}" role="presentation"><a href="#difficulty-{{ $difficulty["id"] }}" aria-controls="difficulty-{{ $difficulty["id"] }}"  role="tab" data-toggle="tab">{{ \TauriBay\Encounter::SIZE_AND_DIFFICULTY_SHORT[$difficulty["id"]] }}</a></li>
                @endforeach
            </ul>
            <div class="tab-content">
                @foreach ( $difficulties as $index => $difficulty )
                    <div role="tabpanel" class="tab-pane {{  $index == $defaultDifficultyIndex ? "active" : "" }}" id="{{ $modeKey }}-difficulty-{{ $difficulty["id"] }}">
                        <div class="encounters_loading"><div class="loader" style="display:block"></div></div>
                        <div data-mode="{{ $modeKey }}" data-difficulty="{{ $difficulty["id"] }}" class="encounter-difficulty-loading-container {{ $index == $defaultDifficultyIndex ? "active" : ""}}"></div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
--}}