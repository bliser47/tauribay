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
                @foreach ( $applied as $character )
                    <div class="{{ $loop->index == 0 ? "memberDataContainerFirst" : "" }} memberDataContainer">
                        <div class="memberDataWidthContainer">
                            <div style="width:{{ $character->percentageScore }}%" class="memberDataWidth memberClass{{ $character->class }}"></div>
                            <div class="memberSpec">
                                <img src="{{ URL::asset("img/classes/specs/" . $character->spec . ".png") }}" alt="{{ $classSpecs[$character->spec] }}"/>
                            </div>
                            <span class="memberPosition">{{ $loop->index+1 }}.</span>
                            <span class="memberName">
                                <a target="_blank" href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$character->realm] . "/" . $character->name . "/" . $character->guid }}">{{ $character->name }}</a>
                            </span>
                            <span class="memberData memberData2">{{ $character->score }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@stop