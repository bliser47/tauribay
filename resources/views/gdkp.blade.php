@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            <div class="panel panel-default">
                <div class="panel-heading nopadding" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            {{ __("Jelentkezés") }}
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        {!! Form::open(array("method" => "post","id"=>"gdkp-apply-form")) !!}
                        <div class="col-sm-4 col-sm-nopadding col-sm-margin">
                            <div id="characters-container" class="input-group col-md-12">
                                {!! Form::select('character_id', $characters, null, ['required', 'id' => 'authorized_characters', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz karaktert")]); !!}
                            </div>
                        </div>
                        <div class="col-sm-4 col-sm-nopadding col-sm-margin">
                            <div id="roles-container" class="input-group col-md-12">
                                {!! Form::select('role_id', $roles, null, ['required', 'id' => 'application_role', 'class' => "control selectpicker input-large", 'placeholder' =>  "Először válassz karaktert"]); !!}
                            </div>
                        </div>
                        <div class="col-sm-4 nomargin col-sm-nopadding">
                            <button class="btn btn-block btn-success" name="filter" value="1" type="submit">
                                {{ __("Jelentkezés") }}
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <table class="table table-bordered table-classes">
                    <tr class="tHead">
                        <th>{{ __("Név") }}</th>
                        <th>{{ __("Kaszt") }}</th>
                        <th>Score</th>
                        <th>Állapot</th>
                    </tr>
                    @foreach ( $applied as $character )
                        <tr id="character{{$character->id}}">
                            <td> <a target="_blank" href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$character->realm] ."/" . $character->name . "/" . $character->guid}}">{{ $character->name }}</a></td>
                            <td class="class-{{ $character->class  }}"> <img src="{{ URL::asset("img/classes/small/" . $character->class . ".png?v=2") }}" alt="{{ $characterClasses[$character->class] }}"/> </td>
                            <td> {{ $character->score }}  </td>
                            <td></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@stop