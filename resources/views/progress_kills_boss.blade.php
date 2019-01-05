@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12 panel table-responsive">
            <table class="table table-bordered">
                <tr>
                    <td width="90px">
                        <a class="btn btn-default" href="{{ URL::to("/progress/kills") }}">
                            {{ __("Vissza") }}
                        </a>
                    </td>
                    <td class="bossName">
                        {{ $bossName }}
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-12">
            <div class="panel table-responsive">
                <table class="table table-bordered table-classes">
                    <tr clas="tHead">
                        <th>{{ __("Nr.") }}</th>
                        <th>{{ __("Realm") }}</th>
                        <th>{{ __("Guild") }}</th>
                        <th>{{ __("Dátum") }}</th>
                        <th>{{ __("Idő") }}</th>
                    </tr>
                    @foreach ( $boss_kills as $kill )
                        <tr class="progressRow" >
                            <td> {{ (($boss_kills->currentPage()-1)*16)+$loop->index+1}} </td>
                            <td> {{ $shortRealms[$kill->realm_id] }} </td>
                            <td class="faction-{{ $kill->faction  }}">
                                @if ( strlen($kill->name) )
                                    <a href="{{ URL::to("progress/guild/" . $kill->realm_id . "/" . $kill->guild_id) }}"> {{ $kill->name }} </a>
                                @else
                                    Random
                                @endif
                            </td>
                            <td>{{ date('M d, Y', $kill->killtime) }}</td>
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
