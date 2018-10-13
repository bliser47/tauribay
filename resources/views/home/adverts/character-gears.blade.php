<ul class="nav nav-tabs" role="tablist">
    <li class="disabled" role="presentation"><a href="#currentgear" aria-controls="currentgear" role="tab" data-toggle="tab">{{__("Jenlegi gear")}}</a></li>
    <li class="disabled" role="presentation"><a href="#newgear" aria-controls="newgear" role="tab" data-toggle="tab"><b>{{__("Új gear hozzáadása")}}</b></a></li>
</ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane" id="newgear">@include('home.adverts.character-gear-new')</div>
    <div role="tabpanel" class="tab-pane" id="currentgear">@include('home.adverts.character-gear')</div>
</div>
