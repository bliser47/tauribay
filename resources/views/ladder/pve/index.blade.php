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
                            <input type="checkbox" checked name="tauri" value="1"/>
                            <input type="checkbox" checked name="wod" value="2"/>
                            <input type="checkbox" checked name="evermoon" value="3"/>
                            <input type="checkbox" checked name="alliance" value="1"/>
                            <input type="checkbox" checked name="horde" value="2"/>
                        </div>
                        @if ( $difficultyId )
                            <input type="hidden" name="difficulty_id" value="{{ $difficultyId }}"/>
                        @endif
                        @if ( $defaultDifficultyId )
                            <input type="hidden" name="default_difficulty_id" value="{{ $defaultDifficultyId }}"/>
                        @endif
                        <input type="hidden" name="encounter_id" value="{{ $encounterId }}"/>
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