@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel-group trade-filter" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading nopadding" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Szűrés
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">

                            {!! Form::open(array("method" => "get","id"=>"characters-form")) !!}


                            <div class="form-group col-md-6 nopadding-left">
                                <legend>Frakció</legend>
                                <div class="input-group">
                                    <div class="checkbox checkbox-inline checkbox-alliance checkbox-white-tick checkbox-faction">
                                        {!! Form::checkbox('alliance',2,Input::get('alliance'),array("id"=>"faction-alliance","class"=>"faction")) !!}
                                        <label for="faction-alliance"> Alliance </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-horde checkbox-white-tick checkbox-faction">
                                        {!! Form::checkbox('horde',1,Input::get('horde'),array("id"=>"faction-horde","class"=>"faction")) !!}
                                        <label for="faction-horde"> Horde </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-unknown checkbox-white-tick checkbox-faction">
                                        {!! Form::checkbox('ismeretlen',3,Input::get('ismeretlen'),array("id"=>"faction-ismeretlen","class"=>"faction")) !!}
                                        <label for="faction-ismeretlen"> Ismeretlen </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-all-factions">
                                        {!! Form::checkbox('factionall',3,Input::get('factionall'),array("id"=>"faction-all","class"=>"faction")) !!}
                                        <label for="faction-all"> Mind </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6 nopadding-right">
                                <legend>Hirdető szándéka</legend>
                                <div class="input-group">
                                    <div class="checkbox checkbox-inline checkbox-intent">
                                        {!! Form::checkbox('elado',1,Input::get('elado'),array("id"=>"intent-sell","class"=>"intent")) !!}
                                        <label for="intent-sell"> Eladás </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-intent">
                                        {!! Form::checkbox('vetel',2,Input::get('vetel'),array("id"=>"intent-buy","class"=>"intent")) !!}
                                        <label for="intent-buy"> Vétel </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-intent">
                                        {!! Form::checkbox('csere',3,Input::get('csere'),array("id"=>"intent-trade","class"=>"intent")) !!}
                                        <label for="intent-trade"> Csere </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-all-intents">
                                        {!! Form::checkbox('intentall',4,Input::get('intentall'),array("id"=>"intent-all","class"=>"intent")) !!}
                                        <label for="intent-all"> Mind </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group class-checkboxes">
                                <legend>Kaszt </legend>
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
                                    <div class="checkbox checkbox-inline checkbox-all-classes">
                                        {!! Form::checkbox('classall',1,Input::get('classall'),array("id"=>"class-all","class"=>"class")) !!}
                                        <label for="class-all"> Mind </label>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group col-md-12">
                                <input type="text" class="form-control" name="search" value="{!! Input::get('search') !!}" placeholder="Keresés a hirdetésben...">
                                  <span class="input-group-btn">
                                    <button class="btn btn-success" name="filter" value="1" type="submit">Szűrés!</button>
                                  </span>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel table-responsive">
                <table class="table table-bordered table-classes">
                    <tr>
                        <th>Idő</th>
                        <th>Eladó</th>
                        <th>Frakció</th>
                        <th>Szándék</th>
                        <th>Kaszt</th>
                        <th>Hirdetés</th>
                    </tr>
                    @foreach ( $characterTrades as $character )
                        <tr>
                            <td class="time" data-time="{{$character->updated_at}}"> {{ $character->updated_at }}</td>
                            <td> <a target="_blank" href="https://tauriwow.com/armory#character-sheet.xml?r=%5BHU%5D%20Tauri%20WoW%20Server&n={{ $character->name }}"> {{ $character->name }} </a></td>
                            <td class="faction-{{ $character->faction  }}"> <img src="{{ URL::asset("img/factions/small/" . $character->faction . ".png") }}" alt=""/> </td>
                            <td> {{ $characterIntents[$character->intent] }}</td>
                            <td class="class-{{ $character->class  }}"> <img src="{{ URL::asset("img/classes/small/" . $character->class . ".png") }}" alt="{{ $characterClasses[$character->class] }}"/> </td>
                            <td> {{ $character->text }} </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="text-center">
                <div>
                    {{ $characterTrades->appends(Illuminate\Support\Facades\Input::except('page')) }}
                </div>
            </div>
        </div>
    </div>
@stop
