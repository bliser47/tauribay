<ul class="nav nav-tabs" role="tablist">
    <li class="home-sub-tab" role="presentation"><a href="#character" aria-controls="character" role="tab" data-toggle="tab">{{__("Karakter")}}</a></li>
    <li class="home-sub-tab disabled" role="presentation"><a><i>{{__("Guild (Soon™)")}}</i></a></li>
</ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane" id="character">@include('home.adverts.character')</div>
    {{--<div role="tabpanel" class="tab-pane" id="guild">@include('home.adverts.guild')</div>--}}
</div>
