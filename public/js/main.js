$(function()
{
    $(".pagination").rPage();

    $(".checkbox-all-classes input").change(function(){
        $(".checkbox-class input").prop('checked', $(this).prop("checked"));
    });
    $(".checkbox-all-factions input").change(function(){
        $(".checkbox-faction input").prop('checked', $(this).prop("checked"));
    });
    $(".checkbox-all-realms input").change(function(){
        $(".checkbox-realm input").prop('checked', $(this).prop("checked"));
    });
    $(".checkbox-all-intents input").change(function(){
        $(".checkbox-intent input").prop('checked', $(this).prop("checked"));
    });
    $(".checkbox-all-instances input").change(function(){
        $(".checkbox-instance input").prop('checked', $(this).prop("checked"));
    });
    $(".checkbox-all-instance-size input").change(function(){
        $(".checkbox-instance-size input").prop('checked', $(this).prop("checked"));
    });
    $(".checkbox-all-instance-difficulty input").change(function(){
        $(".checkbox-instance-difficulty input").prop('checked', $(this).prop("checked"));
    });

    $(".checkbox-all-difficulties input").change(function(){
        $(".checkbox-difficulty input").prop('checked', $(this).prop("checked"));
    });

    $.extend( $.validator.messages, {
        required: VALIDATION_LOCAL_REQUIRED,
        maxlength: $.validator.format( VALIDATION_LOCAL_MAXLENGTH ),
        minlength: $.validator.format( VALIDATION_LOCAL_MINLENGTH ),
        rangelength: $.validator.format( VALIDATION_LOCAL_RANGELENGTH ),
        email: VALIDATION_LOCAL_EMAIL,
        url: VALIDATION_LOCAL_URL,
        date: VALIDATION_LOCAL_DATE,
        number: VALIDATION_LOCAL_NUMBER,
        digits: VALIDATION_LOCAL_DIGIT,
        equalTo: VALIDATION_LOCAL_EQUAL,
        range: $.validator.format(VALIDATION_LOCAL_RANGE),
        max: $.validator.format(VALIDATION_LOCAL_MIN),
        min: $.validator.format(VALIDATION_LOCAL_MAX),
        creditcard: VALIDATION_LOCAL_CREDITCARD,
        remote: VALIDATION_LOCAL_REMOTE,
        dateISO: VALIDATION_LOCAL_DATEISO
    } );

    $.validator.setDefaults({
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block flashit',
        errorPlacement: function(error, element) {

            error.insertAfter(element);
            var errorHeight = error.height();
            error.css("height", 0);
            error.animate({
                height: errorHeight
            }, 500, "easeOutExpo");

        },
        success: function(label) {
            label.closest(".form-group").removeClass('has-error');
            if ( label.height() > 0 )
                label.animate({
                    height: 0,
                    marginTop:0
                }, 500, 'easeOutExpo', function () {
                    $(this).remove();
                });
            else
                $(label).remove();
        }
    });



    $(document).on('submit','newcharacter-form',function(){
        console.log('here');
    });


    $("#newcharacter-form").submit(function(e) {

        $("#newcharacter-form").hide();
        $(".loader").css("display","block");
        sendIlvlAjax($("#newcharacter-form").serialize());
        e.preventDefault();
    });

    function sendIlvlAjax(data)
    {
        $.ajax({
            type: "POST",
            url: URL_WEBSITE + "/ilvl",
            data: data,
            success: function(response)
            {
                if ( response.indexOf("timed out") !== -1)
                {
                    sendIlvlAjax(data);
                }
                else {
                    $(".loader").css("display", "none");
                    $("#newcharacter-form").show();
                    $("#topResponseBody").html(response);
                    $("#topResponseModal").modal('show');
                }
            },
            error: function(xhr, textStatus, errorThrown){
                if ( xhr && xhr.responseText.indexOf("A timeout occurred") !== -1 )
                {
                    sendIlvlAjax(data);
                }
                else {
                    $(".loader").css("display", "none");
                    $("#newcharacter-form").show();
                }
            }
        });
    }

    $('.sortByTop.sortInactive').click(function(){
        $("select[name='sort']").val($(this).data("sort"));
        $(".selectpicker").selectpicker("refresh");
        $("#top-filter-submit").click();
    });

    $(".register-form").validate({
        ignore: [],
        rules : {
            name: { required : true},
            email: {
                required : true,
                email : true
            },
            password: "required",
            password_confirmation: {
                required : true,
                equalTo: "#password"
            },
            hiddenRecaptcha: {
                required: function() {
                    return grecaptcha.getResponse() == '';
                }
            }
        }
    });

    var characterAdvert = $("#new-character-advert");

    characterAdvert.validate({
        ignore: [],
        rules : {
            intent : "required",
            realm : "required",
            name : "required"
        }
    });

    characterAdvert.submit(function(e){

        $.ajax({
            type: "GET",
            url: URL_WEBSITE + "/armory",
            data: $(this).serialize(),
            success: function(response)
            {
                if ( response.length )
                {
                    $('#character-gear-container').html(response);
                    $('#ad-gear-section').removeClass('disabled-ad-section');
                }
            },
            error: function(xhr, textStatus, errorThrown){
            }
        });
        e.preventDefault();
    });

    $(".change-password-form").validate({
        ignore: [],
        rules : {
            password: {
                required: true,
                minlength: 6
            },
            password_confirmation: {
                required : true,
                equalTo: "#password"
            }
        },
        errorPlacement: function(error, element) {
            var errorContainer = "#"+element.attr("name")+"-error-container";
            $(errorContainer).html( error );
            var setWidth = $(element).outerWidth(true);
            if ( element.attr("name") == "password" )
            {
                setWidth += $("#change-password-splitter").outerWidth(true);
            }
            $(errorContainer).css("width",setWidth);
        }
    });

    $(".change-avatar-form").validate({
        ignore: [],
        rules : {
            avatar: "required"
        },
        errorPlacement: function(error, element) {
            $("#avatar-error-container").html(error);
        }
    });

    $('#ad-intent').change(function()
    {
        var adRealm = $('#ad-realm-section');
        adRealm.addClass('disabled-ad-section');

        var buyRealm = $('#buy-realm');
        buyRealm.addClass('inactive-ad-realm');

        var tradeRealm = $('#trade-realm');
        tradeRealm.addClass('inactive-ad-realm');

        var value = this.value;
        if ( value !== undefined && value !== "" ) {
            adRealm.removeClass('disabled-ad-section');
            value = parseInt(value);
            if ( value === 4 )
            {
                buyRealm.removeClass('inactive-ad-realm');
            }
            else
            {
                tradeRealm.removeClass('inactive-ad-realm');
            }
        }
        else
        {
            $('#ad-realm').val(null).change();
        }
    });

    $('#ad-realm').change(function()
    {
        var adCharacterSection = $('#ad-character-section');
        adCharacterSection.addClass('disabled-ad-section');

        var adCharacter = $('#ad-character');
        adCharacter.attr('disabled',true);

        var value = this.value;
        if ( value !== undefined && value !== "" ) {
            adCharacterSection.removeClass('disabled-ad-section');
            adCharacter.attr('disabled', false);
        }
    });


    function sendIlvlAjaxUpdate(form, data, row)
    {
        $(form).hide();
        $(form).parent().find(".update-loader").css("display","block");
        $.ajax({
            type: "POST",
            url: URL_WEBSITE + "/ilvl",
            data: data,
            success: function(response)
            {
                if ( typeof response === "string" && response.indexOf("timed out") !== -1 )
                {
                    sendIlvlAjaxUpdate(data);
                }
                else
                {
                    if ( response.characters ) {
                        var characterData = response.characters[0];
                        if ( characterData )
                        {
                            $(row).find(".topItemLevel").html(characterData.ilvl);
                            $(row).find(".topAchievementPoints").html(characterData.achievement_points);

                            var form = $(row).find(".ilvlupdate-form");
                            form.submit(function (e) {
                                sendIlvlAjaxUpdate($(this), $(this).serialize(), $(this).closest(".charRow"));
                                e.preventDefault();
                            });
                            form.show();
                            form.parent().find(".update-loader").css("display","none");

                            parseTime($(row).find(".time").data('time',characterData.updated_at));
                        }
                    }
                    else
                    {
                        $(row).remove();
                    }
                }
            },
            error: function(xhr, textStatus, errorThrown){
            }
        });
    }

    function sendProgressAjaxUpdate(form, data, row)
    {
        $(form).hide();
        $(form).parent().find(".update-loader").css("display","block");
        $.ajax({
            type: "POST",
            url: URL_WEBSITE + "/guildprogress",
            data: data,
            success: function(response)
            {
                if ( typeof response === "string" && response.indexOf("timed out") !== -1 )
                {
                    sendProgressAjaxUpdate(data);
                }
                else
                {
                    if ( response.difficulty ) {

                        var difficultyId = 5;
                        if ( response.difficulty[6].progress > response.difficulty[5].progress )
                        {
                            difficultyId = 6;
                        }
                        var progress = response.difficulty[difficultyId];

                        $(row).find(".guildProgress").html(progress["progress"]+"/13");

                        var clearTime = parseInt(progress["clear_time"]);
                        if ( clearTime > 0 ) {
                            $(row).find(".guildClearTime").html(clearTime.toString().toHHMMSS());
                        }

                        var form = $(row).find(".progressupdate-form");
                        form.submit(function (e) {
                            sendProgressAjaxUpdate($(this), $(this).serialize(), $(this).closest(".progressRow"));
                            e.preventDefault();
                        });
                        form.show();
                        form.parent().find(".update-loader").css("display","none");
                    }
                    else
                    {
                        $(row).remove();
                    }
                }
            },
            error: function(xhr, textStatus, errorThrown){
            }
        });
    }


    $(".ilvlupdate-form").submit(function(e) {

        sendIlvlAjaxUpdate($(this),$(this).serialize(), $(this).closest('.charRow'));
        e.preventDefault();
    });

    $(".progressupdate-form").submit(function(e) {

        sendProgressAjaxUpdate($(this),$(this).serialize(), $(this).closest('.progressRow'));
        e.preventDefault();
    });

    function parseTime(elem)
    {
        var time = $(elem).data("time");
        var t = time.split(/[- :]/);
        var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
        var now = new Date();
        d.setMinutes(d.getMinutes() - now.getTimezoneOffset());

        var delta = Math.abs(d-now) / 1000;
        var days = Math.floor(delta / 86400);
        delta -= days * 86400;
        var hours = Math.floor(delta / 3600) % 24;
        delta -= hours * 3600;
        var minutes = Math.floor(delta / 60) % 60;
        var seconds = delta % 60;

        var passed = "";
        if ( days > 0 )
        {
            passed = days + TIME_LOCAL_DAY;
        } else if ( hours > 0 )
        {
            passed = hours + TIME_LOCAL_HOUR;
        } else if ( minutes > 0 )
        {
            passed = Math.floor(minutes) + TIME_LOCAL_MINUTE;
        } else if ( seconds > 0 ){
            passed = Math.floor(seconds) + TIME_LOCAL_SECOND;
        }

        $(elem).html(passed);

    }

    String.prototype.toHHMMSS = function () {
        /* extend the String by using prototypical inheritance */
        var seconds = parseInt(this, 10); // don't forget the second param
        var hours   = Math.floor(seconds / 3600);
        var minutes = Math.floor((seconds - (hours * 3600)) / 60);
        seconds = seconds - (hours * 3600) - (minutes * 60);

        if (hours   < 10) {hours   = "0"+hours;}
        if (minutes < 10) {minutes = "0"+minutes;}
        if (seconds < 10) {seconds = "0"+seconds;}
        return hours+':'+minutes+':'+seconds;
    };

    String.prototype.toMMSS = function () {
        /* extend the String by using prototypical inheritance */
        var seconds = parseInt(this, 10); // don't forget the second param
        var hours   = Math.floor(seconds / 3600);
        var minutes = Math.floor((seconds - (hours * 3600)) / 60);
        seconds = seconds - (hours * 3600) - (minutes * 60);

        if (minutes < 10) {minutes = "0"+minutes;}
        if (seconds < 10) {seconds = "0"+seconds;}
        return minutes+':'+seconds;
    };

    function UpdateTimes() {
        $(".time").each(function () {
            $(this).removeClass("time");
            parseTime($(this));
        });
        $(".guildClearTime").each(function () {
            $(this).removeClass("guildClearTime");
            var time = $(this).html();
            if ( time.length > 0 ) {
                $(this).html((parseInt(time)).toString().toHHMMSS());
            }
        });
        $(".encounterKillTime").each(function () {
            $(this).removeClass("encounterKillTime");
            var time = $(this).html();
            if ( time.length > 0 ) {
                $(this).html((parseInt(time)).toString().toMMSS());
            }
        });
    }

    UpdateTimes();

    function bs_input_file() {
        $(".input-file").before(
            function() {
                if ( ! $(this).prev().hasClass('input-ghost') ) {
                    var element = $("<input type='file' class='input-ghost' style='visibility:hidden; height:0'>");
                    element.attr("name",$(this).attr("name"));
                    element.change(function(){
                        element.next(element).find('input').val((element.val()).split('\\').pop());
                    });
                    $(this).find("button.btn-choose").click(function(){
                        element.click();
                    });
                    $(this).find('input').css("cursor","pointer");
                    $(this).find('input').mousedown(function() {
                        $(this).parents('.input-file').prev().click();
                        return false;
                    });
                    return element;
                }
            }
        );
    }
    bs_input_file();

    var listenForMapChange = function()
    {
        $("#maps-container .selectpicker").change(function()
        {
            var set = $(this).val();
            if ( set !== currentMap )
            {
                currentMap = set;
                var encountersContainer = $("#encounter-container");
                var selectPicker = encountersContainer.find(".selectpicker");
                selectPicker.attr('disabled', true);
                selectPicker.val(0);
                selectPicker.selectpicker('refresh');
                if ( currentMap > 0 ) {
                    $.ajax({
                        type: "GET",
                        url: URL_WEBSITE + "/raid/" + currentExpansion + "/" + set,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (encounterSelectHTML) {
                            encountersContainer.html(encounterSelectHTML);
                            selectPicker = encountersContainer.find(".selectpicker");
                            selectPicker.selectpicker('refresh');
                            selectPicker.val(0);
                            selectPicker.selectpicker('refresh');
                        }
                    });
                }
            }
        });
    };
    //listenForMapChange();

    var loadEncounter = function()
    {
        var container = $("#encounter-form-response");
        $(container).html("<div class=\"encounters_loading\"><div class=\"loader\" style=\"display:block\"></div></div>");
        var data = $("#encounter-form").serialize();
        data += "&encounter_id=" + $("select[name='encounter_id'] option:selected").val();
        $.ajax({
            type: "POST",
            url: URL_WEBSITE + "/ladder/pve",
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response)
            {
                response = $.parseJSON(response);
                var newContainer = $("#map-loading-container");
                $(newContainer).html(response["view"]);
                $(".selectpicker").selectpicker();
                prevState = window.location.href;
                history.pushState(null, '', response["url"]  + window.location.hash);
                listenForEncounterFormSubmit();
                handleEncounterModes(newContainer, data);
                UpdateTimes();
            }
        });
    };

    var loadEncounterMode = function(encounterId, page, mode, subForm)
    {
        var container = $("#encounter-form-response-" + mode);
        $(container).html("<div class=\"encounters_loading\"><div class=\"loader\" style=\"display:block\"></div></div>");
        var data = $("#encounter-form").serialize();
        data += "&encounter_id=" + encounterId;
        data += "&page=" + page;
        data += "&mode_id=" + mode;
        if ( subForm && subForm.length )
        {
            data += "&" + subForm;
        }
        $.ajax({
            type: "POST",
            url: URL_WEBSITE + "/ladder/pve",
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response)
            {
                response = $.parseJSON(response);
                $(container).html(response["view"]);
                $(container).find(".pagination a").click(function(e)
                {
                    e.preventDefault();
                    var url = new URL($(this).attr("href"));
                    var page = url.searchParams.get("page");
                    loadEncounterMode(encounterId, page, mode, subForm)
                });
                UpdateTimes();
            }
        });
    };

    var loadMode = function(pane, data, page)
    {
        var tab = $(pane);
        $(tab).html("<div class=\"encounters_loading\"><div class=\"loader\" style=\"display:block\"></div></div>");

        var mode = $(pane).data("mode");

        var storeData = data;
        storeData += "&mode_id=" + mode;
        if ( page !== null )
        {
            storeData += "&page=" + page;
        }
        var difficultyId = $("select[name='difficulty_id'] option:selected").val();
        if (difficultyId) {
            storeData += "&difficulty_id=" + difficultyId;
        }

        $.ajax({
            type: "POST",
            url: URL_WEBSITE + "/ladder/pve",
            data: storeData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {

                response = $.parseJSON(response);
                $(tab).html(response["view"]);
                $(".selectpicker").selectpicker();
                if (mode === "dps" || mode === "hps") {

                    var classVal = $(tab).find("#class").val();
                    if (classVal === "0") {
                        var specPicker = $(tab).find("#spec-container .selectpicker");
                        specPicker.attr('disabled', true);
                        specPicker.val(0);
                        specPicker.selectpicker('refresh');
                    }

                    var encounterId = $("select[name='encounter_id'] option:selected").val();


                    $(tab).find(".encounter-subform-form").submit(function (e) {
                        e.preventDefault();
                        loadEncounterMode(encounterId, 1, mode, $(this).serialize());
                    });
                    $(tab).find(".encounter-subform-form select").change(function () {
                        $(this).parent().submit();
                    });

                    listenForRoleChange(mode);
                    listenForClassChange(mode);

                    loadEncounterMode(encounterId, 1, mode, $(tab).find(".encounter-subform-form").serialize());
                }
                else
                {
                    $(tab).find(".pagination a").click(function(e)
                    {
                        e.preventDefault();
                        var url = new URL($(this).attr("href"));
                        var page = url.searchParams.get("page");
                        loadMode(pane, data, page);
                    });
                }
                UpdateTimes();
            }
        });
    };


    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    var handleEncounterModes = function (container, data)
    {
        listenForTabChange(container);
        var modeSaved = getCookie("modeSaved");
        if ( modeSaved !== "" )
        {
            $(".modePanel, .tab-pane").removeClass("active");
            $("#modePanel" + modeSaved + ", #" + modeSaved).addClass("active");
        }
        $(container).find(".tab-pane").each(function(){

            var pane = $(this);
            if ( $(pane).hasClass("active") ) {
                loadMode(pane,data, 1)
                if ( $(pane).data("mode") === "loot" )
                {
                    $(".encounter-sub-filter-faction, .encounter-sub-filter-realm").hide();
                }
            }
            else
            {
                $("#modePanel" + $(pane).data("mode")).on("click",function(){
                    setCookie("modeSaved",$(this).data("mode"));
                    if ( !$(this).hasClass("loadingMode") )
                    {
                        $(this).addClass("loadingMode");
                        loadMode(pane,data, 1);
                    }
                })
            }
            $("#modePanel" + $(pane).data("mode")).on("click",function(){
                var mode = $(this).data("mode");
                if (mode === "loot") {
                    $(".encounter-sub-filter-faction, .encounter-sub-filter-realm").hide();
                }
                else {
                    $(".encounter-sub-filter-faction, .encounter-sub-filter-realm").show();
                }
            });
        });
    };

    var listenForEncounterFormSubmit = function()
    {
        $("#encounter-form").submit(function(e){
            e.preventDefault();
            loadEncounter();
        });
        $("#encounter-form input, #encounter-form checkbox, #encounter-form select").change(function(){
            $("#encounter-form").submit();
        });
    };

    var listenForClassChange = function(mode, role)
    {
        var currentClass = $("#class").val();
        $("#"+ mode +" #class-container .selectpicker").change(function()
        {
            var set = $(this).val();
            $("#"+ mode + " #class-container .selectpicker").val(set).selectpicker('refresh');
            if ( set !== currentClass )
            {
                currentClass = set;
                $("#" + mode + " #spec-container").each(function(){
                    var selectPicker = $(this).find(".selectpicker");
                    selectPicker.attr('disabled', true);
                    selectPicker.val(0);
                    selectPicker.selectpicker('refresh');
                });
                if ( currentClass > 0 ) {
                    var url = role != null ? (URL_WEBSITE + "/classAndRole/" + role + "/" + set) : (URL_WEBSITE + "/class/" + set);
                    $.ajax({
                        type: "GET",
                        url: url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (classSpecsJson) {

                            classSpecsJson = jQuery.parseJSON(classSpecsJson);

                            $("#" + mode + " #spec-container").each(function(){
                                var selectContainer = $(this);
                                selectContainer.html($(selectContainer).hasClass("short") ? classSpecsJson["mobile"] : classSpecsJson["desktop"]);
                                selectContainer.find(".selectpicker").selectpicker('refresh');
                                selectContainer.attr('disabled', false);
                                $(selectContainer).find(".selectpicker").change(function(){
                                    var set = $(this).val();
                                    $("#" + mode + " #spec-container .selectpicker").val(set).selectpicker('refresh');
                                    $(this).parent().submit();
                                });
                                $(selectContainer).parent().submit();
                            });

                        }
                    });
                }
                else
                {
                    $(this).parent().submit();
                }
            }
        });
    };

    var listenForRoleChange = function(mode)
    {
        var currentRole = $("#role").val();
        $("#"+ mode + " #role-container .selectpicker").change(function()
        {
            var set = $(this).val();
            $("#"+ mode + " #role-container .selectpicker").val(set).selectpicker('refresh');
            if ( set !== currentRole )
            {
                currentRole = set;
                $("#" + mode + " #class-container, #" + mode + " #spec-container").each(function(){
                    var selectPicker = $(this).find(".selectpicker");
                    selectPicker.attr('disabled', true);
                    selectPicker.val(0);
                    selectPicker.selectpicker('refresh');
                });
                $.ajax({
                    type: "GET",
                    url: URL_WEBSITE + "/role/" + set,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(roleClassesSelectsJson)
                    {
                        roleClassesSelectsJson = jQuery.parseJSON(roleClassesSelectsJson);
                        $("#" + mode + " #class-container").each(function(){
                            var selectContainer = $(this);
                            selectContainer.html($(selectContainer).hasClass("short") ? roleClassesSelectsJson["mobile"] : roleClassesSelectsJson["desktop"]);
                            selectContainer.find(".selectpicker").selectpicker('refresh');
                            selectContainer.attr('disabled', false);
                            $(selectContainer).change(function(){
                                $(this).parent().submit();
                            });
                            $(selectContainer).parent().submit();
                        });


                        listenForClassChange(mode, currentRole);
                        UpdateTimes();
                    }
                });
            }
        });
    };

    var currentExpansion = $("#expansion").val();
    var currentMap = $("#map").val();
    $("#expansions-container .selectpicker").change(function()
    {
        var set = $(this).val();
        if ( set !== currentExpansion )
        {
            currentExpansion = set;
            currentMap = null;
            $("#maps-container, #encounter-container").each(function(){
                var selectPicker = $(this).find(".selectpicker");
                selectPicker.attr('disabled', true);
                selectPicker.val(0);
                selectPicker.selectpicker('refresh');
            });
            $.ajax({
                type: "GET",
                url: URL_WEBSITE + "/raid/" + set,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(raidsSelectHTML)
                {
                    var mapsContainer = $("#maps-container");
                    mapsContainer.html(raidsSelectHTML);
                    mapsContainer.find(".selectpicker").selectpicker('refresh');
                    listenForMapChange();
                    UpdateTimes();
                }
            });
        }
    });

    var loadMapDifficulty = function(container, data)
    {
        var difficulty = $(container).data("difficulty");
        if ( difficulty ) {
            data += "&difficulty_id=" + difficulty;
        }
        $.ajax({
            type: "POST",
            url: URL_WEBSITE + "/ladder/pve",
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response)
            {
                response = $.parseJSON(response);
                $(container).find(".encounters_loading").hide();
                $(container).parent().html(response["view"]);
                prevState = window.location.href;
                history.pushState(null, '', response["url"] + window.location.hash);
                UpdateTimes();
            }
        });
    };


    var listenForMapDifficultyLoad = function(data)
    {
        var container = $(".map-difficulty.active").find(".ajax-map-difficulty");
        loadMapDifficulty(container, data);
        $(".map-difficulty-tab").on("click",function(){
            prevState = window.location.href;
            history.pushState(null, '', $(this).data("url") + window.location.hash);
            if ( $(this).hasClass("unLoaded") ) {
                $(this).removeClass("unLoaded");
                var id = $(this).find("a").attr("href");
                loadMapDifficulty($(id).find(".ajax-map-difficulty"), data)
            }
        });
    };

    var listenForTabChange = function(container)
    {
        var hash = window.location.hash;
        if ( hash )
        {
            $(container).find('ul.nav-tabs li').removeClass('active');
            $(container).find('ul.nav-tabs a[href="' + hash + '"]').parent().addClass('active');
            $(container).find(".tab-pane").removeClass("active");
            $(hash).addClass('active');
        }

        $(container).find('.nav-tabs li a').click(function (e) {
            history.replaceState(undefined, undefined, $(this).attr("href"));
            var mode = $(this).parent().data("mode");
            if ( mode )
            {
                setCookie("modeSaved",mode);
            }
        });
    };

    $(".encounters_loading").hide();
    var firstSubmit = true;
    $("#pve-ladder-form").submit(function(e){
        e.preventDefault();
        var container = $("#map-loading-container");
        container.html("");
        var loader = $(".encounters_loading");
        loader.show();
        $("#pve-ladder-filter").attr("disabled",true);
        var data = $(this).serializeArray();
        var keys = ["difficulty_id","encounter_id"];
        if ( !firstSubmit ) {
            for (var k = 0; k < keys.length; ++k) {
                data.forEach(function (value, i) {
                    if (value["name"] === keys[k]) {
                        data.splice(i, 1);
                    }
                });
            }
        }
        firstSubmit = false;
        data = $.param(data);
        setCookie("modeSaved","");
        $.ajax({
            type: "POST",
            url: URL_WEBSITE + "/ladder/pve",
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response)
            {
                response = $.parseJSON(response);
                $(loader).hide();
                $(container).html(response["view"]);

                prevState = window.location.href;
                history.pushState(null, '', response["url"] + window.location.hash);

                $(".selectpicker").selectpicker();
                $(".bossName select[name='map_id']").on("change",function(){
                    var val = $(this).val();
                    if ( val && val > 0 ) {
                        firstSubmit = true;
                        $("#pve-ladder-form select[name='map_id']").val(val);
                        $("#pve-ladder-form").submit();
                    }
                });
                $("#pve-ladder-filter").attr("disabled",false);


                if ( $(container).find("#encounter-form").length )
                {
                    listenForEncounterFormSubmit();
                    handleEncounterModes(container, data);
                }
                else
                {
                    listenForMapDifficultyLoad(data);
                }
                UpdateTimes();
            }
        });
    });
    if ( $("#pve-ladder-form") )
    {
        $("#pve-ladder-form").submit();
    }

    $("#guild-form select").change(function(){
        $("#guild-form").submit();
    });

    var prevState = null;
    $(window).on("popstate", function (e) {
        e.preventDefault();
        location.reload(prevState !== null ? prevState : window.location.href);
    });

    listenForTabChange($("body"));
});