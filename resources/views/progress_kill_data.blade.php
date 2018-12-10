<table class="table table-bordered table-classes">
    <tr>
        <th>{{ __("Wipes") }}</th>
        <th>{{ __("Deaths total") }}</th>
        <th>{{ __("Deaths on kill") }}</th>
        <th>{{ __("Resurrects on kill") }}</th>
    </tr>
    <tr>
        <td>{{ $kill->wipes }}</td>
        <td>{{ $kill->deaths_total }}</td>
        <td>{{ $kill->deaths_fight }}</td>
        <td>{{ $kill->resurrects_fight }}</td>
    </tr>
</table>