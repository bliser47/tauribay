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
                <div id="collapseOne" class="panel-collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        @if ( count($characters) > 0 || count($characterAppliedResults) != 0 )
                            {!! Form::open(array("method" => "post","id"=>"gdkp-apply-form")) !!}
                            <div class="col-sm-4 col-sm-nopadding col-sm-margin">
                                <div id="characters-container" class="input-group col-md-12">
                                    {!! Form::select('character_id', $characters, null, ['required', 'id' => 'authorized_characters', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz karaktert")]); !!}
                                </div>
                            </div>
                            <div class="col-sm-4 col-sm-nopadding col-sm-margin">
                                <div id="roles-container" class="input-group col-md-12">
                                    {!! Form::select('role_id', $roles, null, ['required', 'id' => 'application_role', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Először válassz karaktert") ]); !!}
                                </div>
                            </div>
                            <div class="col-sm-4 nomargin col-sm-nopadding">
                                <button id="gdkp-apply-form-submit" class="btn btn-block btn-success" name="filter" value="1" type="submit">
                                    {{ __("Jelentkezés") }}
                                </button>
                            </div>
                            {!! Form::close() !!}
                        @else
                            <div class="alert alert-warning nomargin">
                                {{ __("Ahoz, hogy jelentkezz legalább 1 karakter hitelesítened kell: ") }}
                                <a href="{{ URL::to("/home#oauth") }}">{{ __("ITT") }}</a>
                            </div>
                        @endif
                    </div>
                </div>
                <ul class="nav nav-tabs" role="tablist">
                    <li class="home-sub-tab active" role="presentation"><a href="#dps" aria-controls="dps" role="tab" data-toggle="tab">{{__("DPS-ek")}}</a></li>
                    <li class="home-sub-tab" role="presentation"><a href="#tanks" aria-controls="tanks" role="tab" data-toggle="tab">{{__("Tankok")}}</a></li>
                    <li class="home-sub-tab" role="presentation"><a href="#heals" aria-controls="heals" role="tab" data-toggle="tab">{{__("Healerek")}}</a></li>
                </ul>
                <div class="tab-content">

                    <div role="tabpanel" class="tab-pane" id="heals">
                        @foreach ( $appliedRoles[\TauriBay\EncounterMember::ROLE_HEAL] as $character )
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
                    <div role="tabpanel" class="tab-pane active" id="dps">
                        @foreach ( $appliedRoles[\TauriBay\EncounterMember::ROLE_DPS] as $character )
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
        </div>
    </div>
@stop