@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            <div class="bossName">
                {{ $playerName }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            <div class="panel">
                <table class="table table-bordered table-classes">
                    <tr class="tHead">
                        <th>{{ __("Név") }}</th>
                        <th>{{ __("Realm") }}</th>
                        <th>{{ __("Kaszt") }}</th>
                        <th>Score</th>
                        <th>iLvL</th>
                        <th>Achi</th>
                        <th class="cellDesktop">GUID</th>
                        <th width=32></th>
                        <th class="cellDesktop">{{ __("Idő") }}</th>
                    </tr>
                    @foreach ( $characters as $character )
                        <tr class="charRow">
                            <td><a href="{{ URL::to("/player/" . \TauriBay\Realm::REALMS_URL[$character->realm] . "/" . $character->name . "/" . $character->guid ) }}">{{ $character->name }}</a></td>
                            <td class="cellDesktop">{{ \TauriBay\Realm::REALMS_SHORT[$character->realm]}}</td>
                            <td class="cellMobile">{{ \TauriBay\Realm::REALMS_SHORTEST[$character->realm]}}</td>
                            <td class="class-{{ $character->class  }}"> <img src="{{ URL::asset("img/classes/small/" . $character->class . ".png") }}" alt="{{ $characterClasses[$character->class] }}"/> </td>
                            <td class="topScore">{{ $character->score  }}</td>
                            <td class="topItemLevel">{{ $character->ilvl  }}</td>
                            <td class="topAchievementPoints">{{ $character->achievement_points  }}</td>
                            <td class="cellDesktop">{{ $character->guid  }}</td>
                            <td>
                                <div class="update-loader" id="updated-loader{{$character->id}}"></div>
                                {!! Form::open(array("method" => "post","class"=>"ilvlupdate-form")) !!}
                                <input type="hidden" name="name" value="{{$character->name}}">
                                <input type="hidden" name="guid" value="{{$character->guid}}">
                                <input type="hidden" name="realm" value="{{$character->realm}}">
                                <input type="hidden" name="refreshTop" value="1">
                                <button class="refreshIlvl" name="updateCharacter" value="1" type="submit"></button>
                                {!! Form::close() !!}
                            </td>
                            <td class="time cellDesktop topUpdateAt" data-time="{{$character->updated_at}}"> {{ $character->updated_at }} </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@stop