<div class="encounter-character-form">
    <div id="encounterMemberFilter{{ $modeId }}" class="encounterMemberFilter">
        {!! Form::open(array("method" => "get","class"=>"encounter-subform-form")) !!}
            <input type="hidden" name="mode_filter" value="1"/>
            <div class="col-xs-4 col-sm-nopadding">
                <div id="role-container" class="input-group col-md-12">
                    {!! Form::select('role_id', $roles,  Input::get('role_id', $roleId), ['required', 'id' => 'role', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz role-t")]); !!}
                </div>
            </div>
            <div class="col-xs-4">
                <div id="class-container" class="input-group col-md-12">
                    {!! Form::select('class_id', $classes,  Input::get('class_id', $classId), ['required', 'id' => 'class', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz kasztot")]); !!}
                </div>
            </div>
            <div class="col-xs-4 col-sm-nopadding">
                <div id="spec-container" class="input-group col-md-12">
                    {!! Form::select('spec_id', $specs,  Input::get('spec_id', $specId), ['required', 'id' => 'spec', 'class' => "disabled control selectpicker input-large", 'placeholder' =>  __("Válassz spec-et")]); !!}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>
<div id="encounter-form-response-{{ $modeId }}">
    <div class="encounters_loading"><div class="loader" style="display:block"></div></div>
    <div class="encounter-loading-container"></div>
</div>