@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel table-responsive">
                <table class="table table-bordered table-classes">
                    <tr class="tHead">
                        <th>{{ __("Idő") }}</th>
                        <th>{{ __("Név") }}</th>
                        <th>{{ __("Realm") }}</th>
                        <th class="cellDesktop">{{ __("Frakció") }}</th>
                        <th>{{ __("Hirdetés") }}</th>
                    </tr>
                    @foreach ( $trades as $trade )
                         <tr>
                            <td class="time" data-time="{{$trade->created_at}}"> {{ $trade->created_at }}</td>

                            <td class="cellDesktop"> <a target="_blank" href="https://tauriwow.com/armory#character-sheet.xml?r=%5BHU%5D%20Tauri%20WoW%20Server&n={{ $trade->name }}"> {{ $trade->name }} </a></td>
                             <td class="cellMobile faction-{{ $trade->faction  }}"> <a target="_blank" href="https://tauriwow.com/armory#character-sheet.xml?r=%5BHU%5D%20Tauri%20WoW%20Server&n={{ $trade->name }}"> {{ $trade->name }} </a></td>

                             <td class="cellMobile">{{ \TauriBay\Realm::REALMS_SHORT[$trade->realm_id] }}</td>
                            <td class="cellDesktop">{{ \TauriBay\Realm::REALMS[$trade->realm_id] }}</td>

                            <td class="cellDesktop faction-{{ $trade->faction  }}"> <img src="{{ URL::asset("img/factions/small/" . $trade->faction . ".png") }}" alt=""/> </td>
                            <td class="text-left"> {{ wordwrap($trade->text,80," ", true) }} </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="text-center">
                <div>
                    {{ $trades->appends(Illuminate\Support\Facades\Input::except('page')) }}
                </div>
            </div>
        </div>
    </div>
@stop
