@if ( count($loots) )
    <table class="table table-bordered table-classes">
        <tr class="tHead">
            <th style="width:35px"></th>
            <th></th>
            <th></th>
            <th class="cellDesktop">{{ __("Név") }}</th>
            <th>{{ __("Típus") }}</th>
            <th>{{ __("iLvL") }}</th>
        </tr>
        @foreach ( $loots as $loot )
            <tr>
                <td class="lootItemContainer">
                    <img class="lootItem" src="https://wow.zamimg.com/images/wow/icons/large/{{ $loot->icon }}.jpg">
                </td>
                <td>
                    {{ \TauriBay\Item::getInventoryType($loot->inventory_type) }}
                </td>
                <td>
                    {{ \TauriBay\Item::getSubClass($loot->inventory_type, $loot->subclass) }}
                </td>
                <td class="cellDesktop" style="white-space:nowrap;">
                    <a class="itemToolTip gearFrame" href="http://mop-shoot.tauri.hu/?item={{ $loot->item_id }}">
                        {{ $loot->name }}
                    </a>
                </td>
                <td> {{ $loot->description }}</td>
                <td style="width:50px;">{{ $loot->ilvl }}</td>
            </tr>
        @endforeach
    </table>
@else
    <div class="alert alert-warning nomargin">
        {{ __("A visszamenőleges loot adatok jelenleg feldolgozás alatt vannak. Kérjük látogass vissza később!") }}
    </div>
@endif