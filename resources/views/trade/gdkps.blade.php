@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            <div class="panel-group trade-filter" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading nopadding" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                {{ __("Szűrés") }}
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">
                            {!! Form::open(array("method" => "get","id"=>"gdkps-form")) !!}
                            <div class="form-group col-md-6 nopadding-left">
                                <legend>{{ __("Frakció") }}</legend>
                                <div class="input-group">
                                    <div class="checkbox checkbox-inline checkbox-alliance checkbox-white-tick checkbox-faction">
                                        {!! Form::checkbox('alliance',\TauriBay\Faction::ALLIANCE,Input::get('alliance'),array("id"=>"faction-alliance","class"=>"faction")) !!}
                                        <label for="faction-alliance"> Alliance </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-horde checkbox-white-tick checkbox-faction">
                                        {!! Form::checkbox('horde',\TauriBay\Faction::HORDE,Input::get('horde'),array("id"=>"faction-horde","class"=>"faction")) !!}
                                        <label for="faction-horde"> Horde </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-unknown checkbox-white-tick checkbox-faction">
                                        {!! Form::checkbox('ismeretlen',\TauriBay\Faction::NEUTRAL,Input::get('ismeretlen'),array("id"=>"faction-ismeretlen","class"=>"faction")) !!}
                                        <label for="faction-ismeretlen">{{ __("Ismeretlen") }}</label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-all-factions">
                                        {!! Form::checkbox('factionall',3,Input::get('factionall'),array("id"=>"faction-all","class"=>"faction")) !!}
                                        <label for="faction-all">{{ __("Mind") }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6 nopadding-right">
                                <legend>{{ __("Hirdető szándéka") }}</legend>
                                <div class="input-group">
                                    <div class="checkbox checkbox-inline checkbox-intent">
                                        {!! Form::checkbox('szervezes',1,Input::get('szervez'),array("id"=>"intent-make","class"=>"intent")) !!}
                                        <label for="intent-make">{{ __("Szervezés") }}</label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-intent">
                                        {!! Form::checkbox('csatlakozas',2,Input::get('vetel'),array("id"=>"intent-join","class"=>"intent")) !!}
                                        <label for="intent-join">{{ __("Csatlakozás") }}</label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-all-intents">
                                        {!! Form::checkbox('intentall',3,Input::get('intentall'),array("id"=>"intent-all","class"=>"intent")) !!}
                                        <label for="intent-all">{{ __("Mind") }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group class-checkboxes">
                                <legend>{{ __("Raid") }}</legend>
                                <div class="input-group">
                                    <div class="checkbox checkbox-inline checkbox-msv checkbox-white-tick checkbox-instance">
                                        {!! Form::checkbox('msv',1,Input::get('msv'),array("id"=>"instance-msv","class"=>"instance")) !!}
                                        <label for="instance-msv"> Mogu'shan Vaults </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-hof checkbox-white-tick checkbox-instance">
                                        {!! Form::checkbox('hof',2,Input::get('hof'),array("id"=>"instance-hof","class"=>"instance")) !!}
                                        <label for="instance-hof"> Heart of Fear </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-toes checkbox-white-tick checkbox-instance">
                                        {!! Form::checkbox('toes',3,Input::get('toes'),array("id"=>"instance-toes","class"=>"instance")) !!}
                                        <label for="instance-toes"> Terrace of Endless Spring </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-tot checkbox-white-tick checkbox-instance">
                                        {!! Form::checkbox('tot',4,Input::get('tot'),array("id"=>"instance-tot","class"=>"instance")) !!}
                                        <label for="instance-tot"> Throne of Thunder </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-all-instances">
                                        {!! Form::checkbox('instanceall',1,Input::get('instanceall'),array("id"=>"instance-all","class"=>"instance")) !!}
                                        <label for="instance-all">{{ __("Mind") }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6 nopadding-left">
                                <legend>{{ __("Méret") }}</legend>
                                <div class="input-group">
                                    <div class="checkbox checkbox-inline checkbox-instance-size">
                                        {!! Form::checkbox('tizes',1,Input::get('tizes'),array("id"=>"instancesize-10","class"=>"instancesize")) !!}
                                        <label for="instancesize-10"> 10 </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-instance-size">
                                        {!! Form::checkbox('huszonotos',2,Input::get('huszonotos'),array("id"=>"instancesize-25","class"=>"instancesize")) !!}
                                        <label for="instancesize-25"> 25 </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-all-instance-size">
                                        {!! Form::checkbox('instancesizeall',3,Input::get('instancesizeall'),array("id"=>"instancesize-all","class"=>"instancesize")) !!}
                                        <label for="instancesize-all">{{ __("Mind") }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6 nopadding-right">
                                <legend>{{ __("Nehézség") }}</legend>
                                <div class="input-group">
                                    <div class="checkbox checkbox-inline checkbox-instance-difficulty">
                                        {!! Form::checkbox('normal',1,Input::get('normal'),array("id"=>"instancedifficulty-normal","class"=>"instancedifficulty")) !!}
                                        <label for="instancedifficulty-normal"> Normal </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-instance-difficulty">
                                        {!! Form::checkbox('heroic',2,Input::get('heroic'),array("id"=>"instancedifficulty-heroic","class"=>"instancedifficulty")) !!}
                                        <label for="instancedifficulty-heroic"> Heroic </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-all-instance-difficulty">
                                        {!! Form::checkbox('instancedifficultyall',3,Input::get('instancedifficultyall'),array("id"=>"instancedifficulty-all","class"=>"instancedifficulty")) !!}
                                        <label for="instancedifficulty-all">{{ __("Mind") }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group col-md-12">
                                <input type="text" class="form-control" name="search" value="{!! Input::get('search') !!}" placeholder="{{ __("Keresés a hirdetésben...") }}">
                                  <span class="input-group-btn">
                                    <button class="btn btn-success" name="filter" value="1" type="submit">{{ __("Szűrés") }}</button>
                                  </span>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="nomargin panel">
                <table class="table table-bordered table-classes table-transparent">
                    <tr class="tHead">
                        <th>Idő</th>
                        <th>{{ __("Név") }}</th>
                        <th>Realm</th>
                        <th class="cellDesktop">{{ __("Frakció") }}</th>
                        <th class="cellDesktop">{{ __("Szándék") }}</th>
                        <th>{{ __("Raid") }}</th>
                        <th>{{ __("Méret") }}</th>
                        <th class="cellDesktop">{{ __("Nehézség") }}</th>
                        <th class="cellMobile">Dif.</th>
                        <th class="headDesktop">{{ __("Hirdetés") }}</th>
                    </tr>
                    @foreach ( $gdkpTrades as $gdkp )
                        <tr>
                            <td class="time" data-time="{{$gdkp->updated_at}}"> {{ $gdkp->updated_at }}</td>
                            <td class="cellDesktop"> <a target="_blank" href="https://tauriwow.com/armory#character-sheet.xml?r={{ \TauriBay\Realm::REALMS[$gdkp->realm_id] }}&n={{ $gdkp->name }}"> {{ $gdkp->name }} </a></td>
                            <td class="cellMobile faction-{{ $gdkp->faction  }}"> <a target="_blank" href="https://tauriwow.com/armory#character-sheet.xml?r={{ \TauriBay\Realm::REALMS[$gdkp->realm_id] }}&n={{ $gdkp->name }}"> {{ \TauriBay\EncounterMember::getShortName($gdkp->name) }} </a></td>
                            <td class="cellDesktop">{{ \TauriBay\Realm::REALMS_SHORT[$gdkp->realm_id] }}</td>
                            <td class="cellMobile">{{ \TauriBay\Realm::REALMS_SHORTEST[$gdkp->realm_id] }}</td>
                            <td class="cellDesktop gdkp-faction faction-{{ $gdkp->faction  }}"> <img src="{{ URL::asset("img/factions/small/" . $gdkp->faction . ".png") }}" alt=""/> </td>
                            <td class="cellDesktop"> {{ __($gdkpIntents[$gdkp->intent]) }}</td>
                            <td class="instance-{{ $gdkp->instance  }}"> {{ \TauriBay\Tauri\WowInstance::WOW_INSTANCE_SHORT_NAMES_NICE[$gdkp->instance] }} </td>
                            <td> {{ $gdkpInstanceSizes[$gdkp->size] }} </td>
                            <td class="instance-difficulty">{{ \TauriBay\Encounter::DIFFICULTY_NAME[$gdkp->difficulty]  }}</td>
                            <td class="cellDesktop tradeMessage"> {{ $gdkp->text }} </td>
                        </tr>
                        <tr class="tradeTextRow rowMobile">
                            <td colspan="6" class="text-left tradeMessage"> {{ $gdkp->text }} </td>
                        </tr>
                        <tr class="rowMobile spacer"><td colspan="6"></td></tr>
                        <tr class="rowMobile spacer"><td colspan="6"></td></tr>
                    @endforeach
                </table>
            </div>
            <div class="text-center">
                <div>
                    {{ $gdkpTrades->appends(Illuminate\Support\Facades\Input::except('page')) }}
                </div>
            </div>
        </div>
    </div>
@stop
