@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading nopadding" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            {{ __("Szűrés") }}
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        {!! Form::open(array("method" => "get","id"=>"pve-ladder-form")) !!}
                        <div class="form-group col-sm-4 col-md-4 col-sm-nopadding">
                            <legend> {{ __("Kieg") }} </legend>
                            <div id="expansions-container" class="input-group col-md-12">
                                {!! Form::select('expansion_id', $expansions, Input::get('expansion_id', $expansionId), ['required', 'id' => 'expansion', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz kieget")]); !!}
                            </div>
                        </div>
                        <div class="form-group col-sm-4 col-md-4 col-sm-nopadding">
                            <legend> {{ __("Raid") }} </legend>
                            <div id="maps-container" class="input-group col-md-12">
                                {!! Form::select('map_id', $maps,  Input::get('map_id', $mapId), ['required', 'id' => 'map', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz raidet")]); !!}
                            </div>
                        </div>
                        <div class="form-group col-sm-4 col-md-4 col-sm-nopadding">
                            <legend> {{ __("Boss") }} </legend>
                            <div id="encounter-container" class="input-group col-md-12">
                                {!! Form::select('encounter_id', $encounters,  Input::get('encounter_id', $encounterId), ['required', 'id' => 'encounter', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz bosst")]); !!}
                            </div>
                        </div>
                        <div class="form-group col-md-12 nomargin col-sm-nopadding">
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