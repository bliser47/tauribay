<ul class="nav nav-tabs" role="tablist">
    <li class="active" role="presentation"><a href="#glyphs-major" aria-controls="character" role="tab" data-toggle="tab">Major</a></li>
    <li role="presentation"><a href="#glyphs-minor" aria-controls="character" role="tab" data-toggle="tab">Minor</a></li>
</ul>
<div class="tab-content">
    <div role="tabpanel" class="active tab-pane" id="glyphs-major">
        <div class="panel-body">
            @yield('class_guide_glyphs_major')
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="glyphs-minor">
        <div class="panel-body">
            @yield('class_guide_glyphs_minor')
        </div>
    </div>
</div>