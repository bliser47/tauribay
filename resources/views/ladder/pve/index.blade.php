@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            <div class="panel panel-default">
                <div class="panel-heading nopadding" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            {{ __("Válassz instát!") }}
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        {!! Form::open(array("method" => "get","id"=>"pve-ladder-form")) !!}
                        <div class="hidden">
                            {!! Form::checkbox('tauri',1,Input::get('tauri')) !!}
                            {!! Form::checkbox('wod',1,Input::get('wod')) !!}
                            {!! Form::checkbox('evermoon',1,Input::get('evermoon')) !!}
                            {!! Form::checkbox('alliance',1,Input::get('alliance')) !!}
                            {!! Form::checkbox('horde',1,Input::get('horde')) !!}
                        </div>
                        @if ( $difficultyId )
                            <input type="hidden" name="difficulty_id" value="{{ $difficultyId }}"/>
                        @endif
                        @if ( $defaultDifficultyId )
                            <input type="hidden" name="default_difficulty_id" value="{{ $defaultDifficultyId }}"/>
                        @endif
                        <input type="hidden" name="expansion_id" value="{{ $expansionId }}"/>
                        <input type="hidden" name="map_id" value="{{ $mapId }}"/>
                        <input type="hidden" name="encounter_id" value="{{ $encounterId }}"/>
                        <input type="hidden" name="role" value="{{ Input::get('role') }}"/>
                        <input type="hidden" name="class" value="{{ Input::get('class') }}"/>
                        <input type="hidden" name="spec" value="{{ Input::get('spec') }}"/>
                        <div class="col-sm-4 col-sm-nopadding col-sm-margin">
                            <div id="expansions-container" class="input-group col-md-12">
                                {!! Form::select('expansion_id', $expansions, Input::get('expansion_id', $expansionId), ['required', 'id' => 'expansion', 'class' => "control selectpicker input-large"]); !!}
                            </div>
                        </div>
                        <div class="col-sm-4 col-sm-nopadding col-sm-margin">
                            <div id="maps-container" class="input-group col-md-12">
                                {!! Form::select('map_id', $maps,  Input::get('map_id', $mapId), ['required', 'id' => 'map', 'class' => "control selectpicker input-large"]); !!}
                            </div>
                        </div>
                        <div class="col-sm-4 nomargin col-sm-nopadding">
                            <button class="btn btn-block btn-success" name="filter" value="1" type="submit">
                                {{ __("Keresés") }}
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="panel table-responsive">
                <div class="encounters_loading"><div class="loader" style="display:block"></div></div>
                <div id="map-loading-container"></div>
            </div>
        </div>
    </div>
@endsection