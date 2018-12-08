@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            @foreach ( $maps as $map )
                <div class="panel-group trade-filter progressKillAccordion" id="accordion{{ $map{"id"} }}" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading nopadding" role="tab" id="headingTwo">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#throneOfThunderTimes" aria-expanded="false" aria-controls="collapseTwo">
                                    {{ $map["name"] }}
                                </a>
                            </h4>
                        </div>
                        <div id="throneOfThunderTimes" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                            <div class="panel table-responsive">
                                <table class="table table-bordered table-classes">
                                    <tr>
                                        <th>{{ __("Realm") }}</th>
                                        <th>{{ __("Guild") }}</th>
                                        <th>{{ __("Boss") }}</th>
                                        <th>{{ __("Idő") }}</th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop
