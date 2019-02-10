@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default panel-white nomargin">
                <canvas id="myChart"></canvas>
            </div>
        </div>
        <div class="col-md-12">
            <div class="bossName">
                Total items dropped in Throne of Thunder: {{ $itemsTotal }}
            </div>
        </div>
        <div class="col-md-12">
            <table class="table table-bordered table-classes table-transparent">
                <tr class="tHead">
                    <td>Encounter</td>
                    <td>Difficulty</td>
                    <td>Item</td>
                    <td>Boss %</td>
                    <td>Total %</td>
                </tr>
                @foreach ( $items as $item )
                    <tr>
                        <td>
                            {{ \TauriBay\Encounter::getNameShort($item->encounter) }}
                        </td>
                        <td>
                            {{ \TauriBay\Encounter::SIZE_AND_DIFFICULTY_SHORT[$item->difficulty] }}
                        </td>
                        <td class="cellDesktop" style="white-space:nowrap;">
                            <a class="itemToolTip gearFrame" href="http://mop-shoot.tauri.hu/?item={{ $item->item_id }}">
                                {{ $item->name }}
                            </a>
                        </td>
                        <td>
                            {{ ($item->num*100)/$bossItems[$item->encounter][$item->difficulty] }}%
                        </td>
                        <td>
                            {{ ($item->num*100)/$itemsTotal }}%
                        </td>
                    </tr>
                @endforeach
            </table>
            <div class="text-center">
                <div>
                    {{ $items->appends(Illuminate\Support\Facades\Input::except('page')) }}
                </div>
            </div>
        </div>
    </div>
@stop
@section("page.footer")
    <script>
        $(function() {
            var ctx = document.getElementById("myChart").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ["Plate", "Mail", "Leather", "Cloth"],
                    datasets: [{
                        label: '% of loot type from Throne of Thunder',
                        data: [{{ implode(",",$loots) }} ],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        });
    </script>
@stop