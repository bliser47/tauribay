<div class="encounter-character-form">
    <div class="panel-heading nopadding" role="tab" id="headingThree{{ $modeId }}">
        <h4 class="panel-title">
            <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion3" href="#encounterMemberFilter{{ $modeId }}" aria-expanded="false" aria-controls="encounterMemberFilter{{ $modeId }}">
                {{ __("Karakter szűrés") }}
            </a>
        </h4>
    </div>
    <div id="encounterMemberFilter{{ $modeId }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree{{ $modeId }}">
        <div class="panel-body">
            {!! Form::open(array("method" => "get","class"=>"encounter-subform-form")) !!}
            <div class="form-group col-md-4 col-sm-nopadding">
                <legend> {{ __("Role") }} </legend>
                <div id="role-container" class="input-group col-md-12">
                    {!! Form::select('role_id', $roles,  Input::get('role_id', $roleId), ['required', 'id' => 'role', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz role-t")]); !!}
                </div>
            </div>
            <div class="form-group col-md-4 col-sm-nopadding">
                <legend> {{ __("Kaszt") }} </legend>
                <div id="class-container" class="input-group col-md-12">
                    {!! Form::select('class_id', $classes,  Input::get('class_id', $classId), ['required', 'id' => 'class', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz kasztot")]); !!}
                </div>
            </div>
            <div class="form-group col-md-4 col-sm-nopadding">
                <legend> {{ __("Spec") }} </legend>
                <div id="spec-container" class="input-group col-md-12">
                    {!! Form::select('spec_id', $specs,  Input::get('spec_id', $specId), ['required', 'id' => 'spec', 'class' => "disabled control selectpicker input-large", 'placeholder' =>  __("Válassz spec-et")]); !!}
                </div>
            </div>
            <div class="form-group col-md-12 col-sm-nopadding nomargin">
                <button class="btn btn-block btn-success" name="filter" value="1" type="submit">
                    {{ __("Szűrés") }}
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<div id="encounter-form-response-{{ $modeId }}">
    <div class="encounters_loading"><div class="loader" style="display:block"></div></div>
    <div class="encounter-loading-container"></div>
</div>