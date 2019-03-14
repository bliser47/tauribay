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

                            {!! Form::open(array("method" => "get","id"=>"characters-form")) !!}

                            <div class="form-group col-md-4">
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

                            <div class="form-group col-md-4">
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

                            <div class="form-group col-md-4">
                                <legend> {{ __("Hirdető szándéka") }}</legend>
                                <div class="input-group">
                                    <div class="checkbox checkbox-inline checkbox-intent">
                                        {!! Form::checkbox('elado',1,Input::get('elado'),array("id"=>"intent-sell","class"=>"intent")) !!}
                                        <label for="intent-sell">  {{ __("Eladás") }} </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-intent">
                                        {!! Form::checkbox('vetel',2,Input::get('vetel'),array("id"=>"intent-buy","class"=>"intent")) !!}
                                        <label for="intent-buy">  {{ __("Vétel") }} </label>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group  col-md-12">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" value="{!! Input::get('search') !!}" placeholder="{{ __("Keresés a hirdetésben...") }}">
                                    <span class="input-group-btn">
                                        <button class="btn btn-success" name="filter" value="1" type="submit">
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
            <div class="nomargin panel table-responsive">
                <table class="table table-bordered table-classes table-transparent">
                    <tr class="tHead">
                        <th>{{ __("Idő") }}</th>
                        <th>{{ __("Név") }}</th>
                        <th>{{ __("Realm") }}</th>
                        <th class="cellDesktop">{{ __("Frakció") }}</th>
                        <th class="cellDesktop">{{ __("Szándék") }}</th>
                        <th class="headDesktop">{{ __("Hirdetés") }}</th>
                    </tr>
                    @foreach ( $creditTrades as $creditTrade )
                        <tr>
                            <td class="time" data-time="{{$creditTrade->updated_at}}"> {{ $creditTrade->updated_at }}</td>
                            <td class="cellDesktop"> <a target="_blank" href="https://tauriwow.com/armory#character-sheet.xml?r=%5BHU%5D%20Tauri%20WoW%20Server&n={{ $creditTrade->name }}"> {{ $creditTrade->name }} </a></td>
                            <td class="cellMobile faction-{{ $creditTrade->faction  }}"> <a target="_blank" href="https://tauriwow.com/armory#character-sheet.xml?r=%5BHU%5D%20Tauri%20WoW%20Server&n={{ $creditTrade->name }}"> {{ $creditTrade->name }} </a></td>
                            <td>{{ \TauriBay\Realm::REALMS_SHORT[$creditTrade->realm_id] }}</td>
                            <td class="cellDesktop faction-{{ $creditTrade->faction  }}"> <img src="{{ URL::asset("img/factions/small/" . $creditTrade->faction . ".png") }}" alt=""/> </td>
                            <td class="cellDesktop"> {{ __($creditIntents[$creditTrade->intent]) }}</td>
                            <td class="cellDesktop text-left tradeMessage">{{ wordwrap($creditTrade->text,80," ", true) }}</td>
                        </tr>
                        <tr class="tradeTextRow rowMobile">
                            <td colspan="3" class="text-left tradeMessage"> {{ wordwrap($creditTrade->text,40," ", true) }} </td>
                        </tr>
                        <tr class="rowMobile spacer"><td colspan="3"></td></tr>
                        <tr class="rowMobile spacer"><td colspan="3"></td></tr>
                    @endforeach
                </table>
            </div>
            <div class="text-center">
                <div>
                    {{ $creditTrades->appends(Illuminate\Support\Facades\Input::except('page')) }}
                </div>
            </div>
        </div>
    </div>
@stop
