@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel-group trade-filter" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel" role="tabpanel" aria-labelledby="headingTwo">
                        <div class="panel-body">
                            {!! Form::open(array("method" => "get","id"=>"pve-ladder-form")) !!}
                            <div class="form-group col-md-6">
                                <legend> {{ __("Kieg") }} </legend>
                                <div class="form-group">
                                    <div id="expansions-container" class="input-group col-md-12">
                                        {!! Form::select('expansion_id', $expansions, Input::get('expansion_id', $expansionId), ['required', 'id' => 'expansion', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz kieget")]); !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <legend> {{ __("Raid") }} </legend>
                                <div class="form-group">
                                    <div id="maps-container" class="input-group col-md-12">
                                        {!! Form::select('map_id', $maps,  Input::get('map_id', $mapId), ['required', 'id' => 'map', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz raidet")]); !!}
                                    </div>
                                </div>
                            </div>
                            {{--
                            <div class="form-group col-md-5">
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
                                    <div class="checkbox checkbox-inline checkbox-all-realms">
                                        {!! Form::checkbox('realmall',3,Input::get('realmall'),array("id"=>"realm-all","class"=>"realm")) !!}
                                        <label for="realm-all"> {{ __("Mind") }} </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-5">
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
                                    <div class="checkbox checkbox-inline checkbox-all-factions">
                                        {!! Form::checkbox('factionall',3,Input::get('factionall'),array("id"=>"faction-all","class"=>"faction")) !!}
                                        <label for="faction-all"> {{ __("Mind") }} </label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group col-md-12">
                                <input type="text" class="form-control" name="search" value="{!! Input::get('search') !!}" placeholder="{{ __("Karakter neve...") }}">
                                <span class="input-group-btn">
                                    <button id="pve-ladder-filter" class="btn btn-success" name="filter" value="1" type="submit">
                                        {{ __("Szűrés") }}
                                    </button>
                                  </span>
                            </div>
                            --}}
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