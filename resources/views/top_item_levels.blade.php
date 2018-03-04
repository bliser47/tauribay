@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel-group trade-filter" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading nopadding" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                {{ __("Karakter/Guild hozzáadása") }}
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">
                            <div class="loader"></div>
                            {!! Form::open(array("method" => "post","id"=>"newcharacter-form")) !!}
                            <div class="form-group">
                                <h4> {{ __("1. Válaszd ki a realmet!") }} </h4>
                                <div class="input-group col-md-12">
                                    {!! Form::select('realm', $realms, null, ['required', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz realmet")]); !!}
                                </div>
                                <div class="input-group col-md-12">
                                    <h4> {{ __("2. Add meg a guild és/vagy a karakter nevét") }} </h4>
                                    <input type="text" class="form-control" name="guildName" value="{!! Input::get('guildName') !!}" placeholder="{{ __("Guild neve") }}">
                                    <p style="display:table;margin:auto;padding:10px">{{ __("és/vagy") }}</p>
                                    <input type="text" class="form-control" name="name" value="{!! Input::get('name') !!}" placeholder="{{ __("Karakter neve") }}">
                                </div>
                            </div>

                            <div class="input-group col-md-12">
                                <button class="btn btn-block btn-success" name="add" value="1" type="submit">
                                    {{ __("Hozzáadás") }}
                                </button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel table-responsive">
                <table class="table table-bordered table-classes">
                    <tr>
                        <th>{{ __("Idő") }}</th>
                        <th>{{ __("Realm") }}</th>
                        <th>{{ __("Név") }}</th>
                        <th>{{ __("Frakció") }}</th>
                        <th>{{ __("Kaszt") }}</th>
                        <th>{{ __("iLvL") }}</th>
                    </tr>
                    @foreach ( $characters as $character )
                        <tr>
                            <td class="time" data-time="{{$character->updated_at}}"> {{ $character->updated_at }}</td>
                            <td> {{ $realmsShort[$character->realm]  }}</td>
                            <td> <a target="_blank" href="https://tauriwow.com/armory#character-sheet.xml?r={{ $realms[$character->realm] }}&n={{ $character->name }}"> {{ $character->name }} </a></td>
                            <td class="faction-{{ $character->faction  }}"> <img src="{{ URL::asset("img/factions/small/" . $character->faction . ".png") }}" alt=""/> </td>
                            <td class="class-{{ $character->class  }}"> <img src="{{ URL::asset("img/classes/small/" . $character->class . ".png") }}" alt="{{ $characterClasses[$character->class] }}"/> </td>
                            <td> {{ $character->ilvl }} </td>
                        </tr>
                    @endforeach
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
