@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            <div class="panel panel-default nomargin">
                <ul class="nav nav-tabs" role="tablist">
                    @foreach ( $realms as $realmId => $realm )
                      <li class="home-main-tab {{ $realm["active"] ? "active" : "" }}" role="presentation"><a href="#{{ strtolower($realmId) }}" aria-controls="{{ $realmId }}" role="tab" data-toggle="tab">{{ $realmId }}</a></li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    @foreach ( $realms as $realmId => $realm )
                        <div role="tabpanel" class="tab-pane {{ $realm["active"] ? "active" : "" }}" id="{{ strtolower($realmId) }}">
                            <table class="table table-bordered table-classes table-transparent">
                                <tr class="tHead">
                                    <td style="width:35px"></td>
                                    <td class="cellDesktop">Slot</td>
                                    <td class="cellDesktop"></td>
                                    <td>Item</td>
                                    <td class="cellDesktop"></td>
                                    <td>iLvL</td>
                                    <td>{{ __("Idő") }}</td>
                                </tr>
                                @foreach ( $realm["items"] as $item )
                                    <tr>
                                        <td class="lootItemContainer">
                                            <img class="lootItem" src="https://wow.zamimg.com/images/wow/icons/large/{{ $item->icon }}.jpg">
                                        </td>
                                        <td class="cellDesktop">
                                            {{ \TauriBay\Item::getInventoryType($item->inventory_type) }}
                                        </td>
                                        <td class="cellDesktop">
                                            {{ \TauriBay\Item::getSubClass($item->inventory_type, $item->subclass) }}
                                        </td>
                                        <td style="white-space:nowrap;">
                                            <a class="itemToolTip gearFrame" href="http://mop-shoot.tauri.hu/?item={{ $item->item_id }}">
                                                {{ strlen($item->name) > 25 ? mb_substr($item->name,0,22) . ".." : $item->name }}
                                            </a>
                                        </td>
                                        <td class="cellDesktop"> {{ $item->description }}</td>
                                        <td style="width:50px;">{{ $item->ilvl }}</td>
                                        <td>{!! $item->timeLeft !!} </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@stop