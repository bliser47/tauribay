<table class="table table-bordered table-classes table-transparent">
    <tr class="tHead">
        <td style="width:35px"></td>
        <td></td>
        <td></td>
        <td class="cellDesktop">Item</td>
        <td class="cellDesktop"></td>
        <td></td>
        <td>%</td>
    </tr>
    @foreach ( $items as $item )
        <tr>
            <td class="lootItemContainer">
                <img class="lootItem" src="https://wow.zamimg.com/images/wow/icons/large/{{ $item->icon }}.jpg">
            </td>
            <td>
                {{ \TauriBay\Item::getInventoryType($item->inventory_type) }}
            </td>
            <td>
                {{ \TauriBay\Item::getSubClass($item->inventory_type, $item->subclass) }}
            </td>
            <td class="cellDesktop" style="white-space:nowrap;">
                <a class="itemToolTip gearFrame" href="http://mop-shoot.tauri.hu/?item={{ $item->item_id }}">
                    {{ $item->name }}
                </a>
            </td>
            <td class="cellDesktop"> {{ $item->description }}</td>
            <td style="width:50px;">{{ $item->ilvl }}</td>
            <td>
                {{ number_format(($item->num*100)/$itemsTotal,2) }}%
            </td>
        </tr>
    @endforeach
</table>