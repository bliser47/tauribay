@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12 bossKillContainer">
            <a class="btn btn-default" href="{{ URL::to("/progress/kills") }}">
                {{ __("Vissza") }}
            </a>
            <h1>{{ $bossName }}</h1>
        </div>
        <div class="col-md-12">
            <div class="panel table-responsive">
                <table class="table table-bordered table-classes">
                    <tr>
                        <th>{{ __("Nr.") }}</th>
                        <th>{{ __("Realm") }}</th>
                        <th>{{ __("Guild") }}</th>
                        <th>{{ __("Frakció") }}</th>
                        <th>{{ __("Idő") }}</th>
                    </tr>
                    @foreach ( $boss_kills as $kill )
                        <tr class="progressRow rowDesktop">
                            <td> {{ (($boss_kills->currentPage()-1)*16)+$loop->index+1}} </td>
                            <td> {{ $shortRealms[$kill->realm_id] }} </td>
                            <td>
                                @if ( strlen($kill->name) )
                                    <a target="_blank" href="https://tauriwow.com/armory#guild-info.xml?r={{ $longRealms[$kill->realm_id] }}&gn={{ $kill->name }}">{{ $kill->name }}</a>
                                @else
                                    Random
                                @endif
                            </td>
                            <td class="faction-{{ $kill->faction  }}">
                                <img src="{{ URL::asset("img/factions/small/" . ($kill->faction == 1 ? 1 : 2) . ".png") }}" alt=""/>
                            </td>
                            <td><a class="guildClearTime" href="{{ URL::to("/progress/kill/") . "/" . $kill->id }}">{{ $kill->fight_time/1000  }}</a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="text-center">
                <div>
                    {{ $boss_kills->appends(Illuminate\Support\Facades\Input::except('page')) }}
                </div>
            </div>
        </div>
    </div>
@stop
