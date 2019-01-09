@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel table-responsive">
                <table class="table table-bordered table-classes">
                    <tr>
                        <th>{{ __("Realm") }}</th>
                        <th>{{ __("Guild") }}</th>
                        <th>{{ __("Boss") }}</th>
                        <th>{{ __("Idő") }}</th>
                    </tr>
                    @foreach ( $data as $d )
                        <tr>
                            <th> {{ $shortRealms[$d->realm_id] }} </th>
                            <th> {{ TauriBay\Guild::getName($d->guild_id) }} </th>
                            <th> {{ TauriBay\Encounter::getName( $d->encounter_id)  }} </th>
                            <th> {{ date('i:s', mktime(0, 0, $d->fight_time/1000))}} </th>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="text-center">
                <div>
                    {{ $data->appends(Illuminate\Support\Facades\Input::except('page')) }}
                </div>
            </div>
        </div>
    </div>
@stop
