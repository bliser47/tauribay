@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            <div class="panel-group trade-filter" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading nopadding" role="tab" id="headingTwo">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                {{ __("Szűrés") }}
                            </a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                        <div class="panel-body">

                            {!! Form::open(array("method" => "get","id"=>"top-filter-form")) !!}
                            <div class="form-group col-md-2">
                                <legend> {{ __("Rendezés") }} </legend>
                                <div id="top-sort-by" class="form-group">
                                    <div class="input-group col-md-12">
                                        {!! Form::select('sort', $sortBy, (!Input::has('sort') || Input::get('sort') == 'level') ? 'ilvl'  : 'achievement_points', ['required', 'id' => 'sort-by', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz rendezést")]); !!}
                                     </div>
                                 </div>
                            </div>
                            <div class="form-group col-md-5">
                                 <legend> {{ __("Realm") }} </legend>
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
                            <div class="form-group col-md-5">
                                <legend> {{ __("Frakció") }} </legend>
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
                            <div class="form-group col-md-12 class-checkboxes">
                                <legend> {{ __("Kaszt") }} </legend>
                                <div class="input-group">
                                    <div class="checkbox checkbox-inline checkbox-warrior checkbox-white-tick checkbox-class">
                                        {!! Form::checkbox('warrior',2,Input::get('warrior'),array("id"=>"class-warrior","class"=>"class")) !!}
                                        <label for="class-warrior"> Warrior </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-paladin checkbox-white-tick checkbox-class">
                                        {!! Form::checkbox('paladin',1,Input::get('paladin'),array("id"=>"class-paladin","class"=>"class")) !!}
                                        <label for="class-paladin"> Paladin </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-hunter checkbox-white-tick checkbox-class">
                                        {!! Form::checkbox('hunter',1,Input::get('hunter'),array("id"=>"class-hunter","class"=>"class")) !!}
                                        <label for="class-hunter"> Hunter </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-rogue checkbox-class">
                                        {!! Form::checkbox('rogue',1,Input::get('rogue'),array("id"=>"class-rogue","class"=>"class")) !!}
                                        <label for="class-rogue"> Rogue </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-class">
                                        {!! Form::checkbox('priest',1,Input::get('priest'),array("id"=>"class-priest","class"=>"class")) !!}
                                        <label for="class-priest"> Priest </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-deathknight checkbox-white-tick checkbox-class">
                                        {!! Form::checkbox('dk',1,Input::get('dk'),array("id"=>"class-deathknight","class"=>"class")) !!}
                                        <label for="class-deathknight"> DK </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-shaman checkbox-white-tick checkbox-class">
                                        {!! Form::checkbox('shaman',1,Input::get('shaman'),array("id"=>"class-shaman","class"=>"class")) !!}
                                        <label for="class-shaman"> Shaman </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-mage checkbox-white-tick checkbox-class">
                                        {!! Form::checkbox('mage',1,Input::get('mage'),array("id"=>"class-mage","class"=>"class")) !!}
                                        <label for="class-mage"> Mage </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-warlock checkbox-white-tick checkbox-class">
                                        {!! Form::checkbox('warlock',1,Input::get('warlock'),array("id"=>"class-warlock","class"=>"class")) !!}
                                        <label for="class-warlock"> Warlock </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-druid checkbox-white-tick checkbox-class">
                                        {!! Form::checkbox('druid',1,Input::get('druid'),array("id"=>"class-druid","class"=>"class")) !!}
                                        <label for="class-druid"> Druid </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-monk checkbox-white-tick checkbox-class">
                                        {!! Form::checkbox('monk',1,Input::get('monk'),array("id"=>"class-monk","class"=>"class")) !!}
                                        <label for="class-monk"> Monk </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group  col-md-12">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" value="{!! Input::get('search') !!}" placeholder="{{ __("Karakter neve...") }}">
                                    <span class="input-group-btn">
                                        <button id="top-filter-submit" class="btn btn-success" name="filter" value="1" type="submit">
                                            {{ __("Szűrés") }}
                                        </button>
                                      </span>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel table-responsive nomargin">
                <table class="table table-bordered table-classes">
                    <tr class="tHead">
                        <th>{{ __("Nr") }}</th>
                        <th class="cellDesktop">{{ __("Realm") }}</th>
                        <th>{{ __("Név") }}</th>
                        <th>{{ __("Kaszt") }}</th>
                        <th><a id="sortByLevel" data-sort="ilvl" class="sortByTop {{ (!Input::has('sort') || Input::get('sort') == 'ilvl') ? 'sortActive'  : 'sortInactive' }}">{{ __("iLvL") }}</a></th>
                        <th><a id="sortByAchi" data-sort="achievement_points" class="sortByTop {{ (Input::has('sort') && Input::get('sort') == 'achievement_points') ? 'sortActive'  : 'sortInactive' }}">{{ __("Achi") }}</a></th>
                        <th><a id="sortByScore" data-sort="score" class="sortByTop {{ (Input::has('sort') && Input::get('sort') == 'score') ? 'sortActive'  : 'sortInactive' }}">{{ __("Score") }}</a></th>
                        <th class="cellDesktop" width=32>{{ __("Frissítés") }}</th>
                        <th class="cellMobile" width=32></th>
                        <th class="cellDesktop">{{ __("Idő") }}</th>
                    </tr>
                    @include('top_item_levels_characters')
                </table>
            </div>
            <div class="text-center">
                <div>
                    {{ $characters->appends(Illuminate\Support\Facades\Input::except('page')) }}
                </div>
            </div>
        </div>
    </div>
@stop