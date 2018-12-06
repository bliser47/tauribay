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

                    @endforeach
                </table>
            </div>
        </div>
    </div>
@stop
