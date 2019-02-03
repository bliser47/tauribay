<div class="encounter-character-form">
    <div id="encounterMemberFilter{{ $modeId }}" class="encounterMemberFilter">
        {!! Form::open(array("method" => "get","class"=>"encounter-subform-form")) !!}
            <input type="hidden" name="mode_filter" value="1"/>
            <div class="divDesktop selectParent">
                <div class="col-xs-4 col-sm-nopadding">
                    <div id="role-container" class="input-group col-md-12">
                        {!! Form::select('role_id', $roles,  Input::get('role_id', $roleId), ['required', 'id' => 'role', 'class' => "control selectpicker input-large"]); !!}
                    </div>
                </div>
                <div class="col-xs-4">
                    <div id="class-container" class="input-group col-md-12">
                        {!! Form::select('class_id', $classes,  Input::get('class_id', $classId), ['required', 'id' => 'class', 'class' => "control selectpicker input-large"]); !!}
                    </div>
                </div>
                <div class="col-xs-4 col-sm-nopadding">
                    <div id="spec-container" class="input-group col-md-12">
                        {!! Form::select('spec_id', $specs,  Input::get('spec_id', $specId), ['required', 'id' => 'spec', 'class' => "disabled control selectpicker input-large"]); !!}
                    </div>
                </div>
            </div>
            <div class="divMobile selectParent">
                <div class="col-xs-4 col-sm-nopadding">
                    <div id="role-container" class="short input-group col-md-12">
                        {!! Form::select('role_id', $rolesShort,  Input::get('role_id', $roleId), ['required', 'id' => 'role', 'class' => "control selectpicker input-large"]); !!}
                    </div>
                </div>
                <div class="col-xs-4">
                    <div id="class-container" class="short input-group col-md-12">
                        {!! Form::select('class_id', $classesShort,  Input::get('class_id', $classId), ['required', 'id' => 'class', 'class' => "control selectpicker input-large"]); !!}
                    </div>
                </div>
                <div class="col-xs-4 col-sm-nopadding">
                    <div id="spec-container" class="short input-group col-md-12">
                        {!! Form::select('spec_id', $specsShort,  Input::get('spec_id', $specId), ['required', 'id' => 'spec', 'class' => "disabled control selectpicker input-large"]); !!}
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>
<div id="encounter-form-response-{{ $modeId }}" class="encounter-form-response-mode">
    <div class="encounters_loading"><div class="loader" style="display:block"></div></div>
    <div class="encounter-loading-container"></div>
</div>