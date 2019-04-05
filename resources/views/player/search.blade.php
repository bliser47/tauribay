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
                        <th>GUID</th>
                        <th></th>
                    </tr>
                    @foreach ( $characters as $character )
                        <tr>
                            <td>{{ $character->name }}</td>
                            <td>{{ \TauriBay\Realm::REALMS_SHORT[$character->realm]}}</td>
                            <td class="class-{{ $character->class  }}"> <img src="{{ URL::asset("img/classes/small/" . $character->class . ".png") }}" alt="{{ $characterClasses[$character->class] }}"/> </td>
                            <td>{{ $character->guid  }}</td>
                            <td><a href="{{ URL::to("/player/" . \TauriBay\Realm::REALMS_URL[$character->realm] . "/" . $character->name . "/" . $character->guid ) }}">{{ __("Kiválaszt") }}</a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@stop