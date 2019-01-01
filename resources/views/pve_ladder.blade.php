@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel-group trade-filter" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel" role="tabpanel" aria-labelledby="headingTwo">
                        <div class="panel-body">
                            {!! Form::open(array("method" => "get","id"=>"pve-ladder-form")) !!}
                            <div class="form-group col-md-4">
                                <legend> {{ __("Kieg") }} </legend>
                                <div class="form-group">
                                    <div id="expansions-container" class="input-group col-md-12">
                                        {!! Form::select('expansion_id', $expansions, Input::get('expansion_id', $expansionId), ['required', 'id' => 'expansion', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz kieget")]); !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <legend> {{ __("Raid") }} </legend>
                                <div class="form-group">
                                    <div id="maps-container" class="input-group col-md-12">
                                        {!! Form::select('map_id', $maps,  Input::get('map_id', $mapId), ['required', 'id' => 'map', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz raidet")]); !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <legend> {{ __("Nehézség") }} </legend>
                                <div class="form-group">
                                    <div id="difficulty-container" class="input-group col-md-12">
                                        {!! Form::select('difficulty_id', $difficulties,  Input::get('difficulty_id', $difficultyId), ['required', 'id' => 'size', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz nehézséget")]); !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <button class="btn btn-block btn-success" name="filter" value="1" type="submit">
                                    {{ __("Keresés") }}
                                </button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default nomargin">
                <div class="encounters_loading"><div class="loader" style="display:block"></div></div>
                <div id="map-loading-container"></div>
            </div>
        </div>
    </div>
@endsection