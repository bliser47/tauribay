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
                            $(row).find(".topScore").html(characterData.score.toFixed(2));

                            var form = $(row).find(".ilvlupdate-form");
                            form.submit(function (e) {
                                sendIlvlAjaxUpdate($(this), $(this).serialize(), $(this).closest(".charRow"));
                                e.preventDefault();
                            });
                            form.show();
                            form.parent().find(".update-loader").css("display","none");

                            parseTime($(row).find(".topUpdateAt").data('time',characterData.updated_at));
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
                    $(row).find(".guildProgress").html(response["progress"]);

                    var clearTime = parseInt(response["clear_time"]);
                    if ( clearTime > 0 ) {
                        $(row).find(".guildClearTimeContainer").html(clearTime.toString().toHHMMSS());
                    }

                    var form = $(row).find(".progressupdate-form");
                    form.show();
                    form.parent().find(".update-loader").css("display","none");
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
        $(".guildClearTimeMobile").each(function () {
            $(this).removeClass("guildClearTimeMobile");
            var time = $(this).html();
            if ( time.length > 0 ) {
                $(this).html((parseInt(time)).toString().toMMSS());
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
                $(newContainer).html(replaceTranslations(response["view"]));
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
                $(container).html(replaceTranslations(response["view"]));
                $(container).find(".pagination a").click(function(e)
                {
                    e.preventDefault();
                    var url = new URL($(this).attr("href"));
                    var page = url.searchParams.get("page");
                    loadEncounterMode(encounterId, page, mode, subForm)
                });

                $(container).find(".refreshHeader").click(function(e){
                    subForm += "&refresh_cache=1";
                    loadEncounterMode(encounterId, 1, mode, subForm);
                    e.preventDefault();
                });

                prevState = window.location.href;
                history.pushState(null, '', response["url"] + window.location.hash);

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
                $(tab).html(replaceTranslations(response["view"]));
                $(".selectpicker").selectpicker();
                if ( mode === "dps" || mode === "hps" || mode === "recent" || mode === "speed" ) {
                    prevState = window.location.href;
                    history.pushState(null, '', response["url"] + window.location.hash);
                }
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
                    $(tab).find(".encounter-subform-form").on("change", "select", function () {
                        $("input[type='hidden'][name='" + $(this).attr("id") + "']").val($(this).val());
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

                    if ( mode === "speed" ) {
                        $(tab).find(".refreshHeader").click(function(e){
                            data += "&refresh_cache=1";
                            loadMode(pane, data, page);
                            e.preventDefault();
                        });
                    }
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
        var modeSaved = getCookie("modeSaved2");
        if ( modeSaved !== "" )
        {
            $(".modePanel, .tab-pane").removeClass("active");
            $("#modePanel" + modeSaved + ", #" + modeSaved).addClass("active");
        }
        $(container).find(".tab-pane").each(function(){

            var pane = $(this);
            if ( $(pane).hasClass("active") ) {
                loadMode(pane,data, 1);
                var mode = $(pane).data("mode");
                if ( mode === "loot" )
                {
                    $(".encounter-sub-filter-faction, .encounter-sub-filter-realm, .encounter-sub-filter-maxplayers").hide();
                } else if ( mode !== "recent") {
                    $(".encounter-sub-filter-maxplayers").hide();
                }
            }
            else
            {
                $("#modePanel" + $(pane).data("mode")).on("click",function(){
                    setCookie("modeSaved2",$(this).data("mode"));
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
                    if ( mode === "recent" ) {
                        $(".encounter-sub-filter-maxplayers").show();
                    } else {
                        $(".encounter-sub-filter-maxplayers").hide();
                    }
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
            $("input[type='hidden'][name='class").val($(this).val());
            if ( set !== currentClass )
            {
                currentClass = set;
                $("#" + mode + " #spec-container").each(function(){
                    var selectPicker = $(this).find(".selectpicker");
                    selectPicker.attr('disabled', true);
                    selectPicker.val(0);
                    selectPicker.selectpicker('refresh');
                    $("input[type='hidden'][name='spec").val($(this).val());
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
                                    $("input[type='hidden'][name='spec']").val(set);
                                });
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


    $("#authorized_characters").change(function()
    {
        var char_id = $(this).val();
        $.ajax({
            type: "GET",
            url: URL_WEBSITE + "/raidChar/" + char_id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(rolesSelect)
            {
                $("#roles-container").each(function(){
                    var selectContainer = $(this);
                    selectContainer.html(rolesSelect);
                    selectContainer.find(".selectpicker").selectpicker('refresh');
                    selectContainer.attr('disabled', false);
                });

                listenForClassChange(mode, currentRole);
                UpdateTimes();
            }
        });
    });

    var listenForRoleChange = function(mode)
    {
        var currentRole = $("#role").val();
        $("#"+ mode + " #role-container .selectpicker").change(function()
        {
            var set = $(this).val();
            $("#"+ mode + " #role-container .selectpicker").val(set).selectpicker('refresh');
            $("input[type='hidden'][name='role']").val(set);
            if ( set !== currentRole )
            {
                currentRole = set;
                $("#" + mode + " #class-container, #" + mode + " #spec-container").each(function(){
                    var selectPicker = $(this).find(".selectpicker");
                    selectPicker.attr('disabled', true);
                    selectPicker.val(0);
                    selectPicker.selectpicker('refresh');

                    $("input[type='hidden'][name='" + $(selectPicker).attr("id") + "']").val($(this).val());
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
                                var newClassId = $(this).find(".selectpicker").val();
                                $("input[type='hidden'][name='class']").val(newClassId);
                            });
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
                $(container).parent().html(replaceTranslations(response["view"]));
                prevState = window.location.href;
                history.pushState(null, '', response["url"] + window.location.hash);
                UpdateTimes();

                if ( $(container).find("difficulty-form-response") )
                {
                    listenForDifficultyMode(difficulty,data);
                }
            }
        });
    };

    var refreshMapDifficultySpec = function(form) {
        $(form).find(".refreshProgress").hide();
        $(form).find(".update-loader").css("display","block");
        $.ajax({
            type: "GET",
            url: $(form).attr("action"),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response)
            {
                $(form).find(".refreshProgress").css("display","block").show();
                $(form).find(".update-loader").hide();
                $(form).parent().parent().find(".memberDataWidthContainer").html(replaceTranslations(response));
            }
        });
    };

    var loadPlayerMapDifficulty = function(container, realm, name, guid, mode, expansion, map)
    {
        var difficulty = $(container).data("difficulty");
        $.ajax({
            type: "GET",
            url: URL_WEBSITE + "/player/" + realm +  "/" + name + "/" + guid + "/" + mode + "/" + expansion + "/" + map + "/" + difficulty,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response)
            {
                response = $.parseJSON(response);
                $(container).find(".encounters_loading").hide();
                $(container).parent().html(replaceTranslations(response["view"])).find(".refreshSpecTop").each(function(){
                    if ( $(this).hasClass("autoLoad") ) {
                        refreshMapDifficultySpec($(this));
                    }
                    $(this).submit(function(e){
                        e.preventDefault();
                        refreshMapDifficultySpec($(this));
                    })
                });
            }
        });
    };


    var listenForMapDifficultyLoad = function(data)
    {
        $("#ladder-filter-form").submit(function(e){
            e.preventDefault();
            $("input[name='tauri']").prop("checked",$("#realm-tauri").is(':checked'));
            $("input[name='wod']").prop("checked",$("#realm-wod").is(':checked'));
            $("input[name='evermoon']").prop("checked",$("#realm-evermoon").is(':checked'));
            $("input[name='alliance']").prop("checked",$("#faction-alliance").is(':checked'));
            $("input[name='horde']").prop("checked",$("#faction-horde").is(':checked'));
            $("input[name='max_players']").val($("#max-players").val());
            $("#pve-ladder-form").submit();
        });
        $("#ladder-filter-form input").change(function(){
            $("#ladder-filter-form").submit();
        });
        var diffSaved = getCookie("diffSaved2");
        if ( diffSaved !== "" )
        {
            if ( $("#difficulty-" + diffSaved).length > 0 ) {
                $(".map-difficulty.active, .map-difficulty-tab.active").removeClass("active");
                $("#difficultyPanel" + diffSaved + ", #difficulty-" + diffSaved).addClass("active loaded")
            }
        }
        var container = $(".map-difficulty.active").find(".ajax-map-difficulty");
        loadMapDifficulty(container, data);
        $(".map-difficulty-tab").on("click",function(){
            prevState = window.location.href;
            history.pushState(null, '', $(this).data("url") + window.location.hash);
            var diff = $(this).data("diff");
            if ( diff !== null && diff !== "")
            {
                setCookie("diffSaved2",diff);
            }
            if ( !$(this).hasClass("loaded") ) {
                $(this).addClass("loaded");
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
                setCookie("modeSaved2",mode);
            }
        });
    };

    var loadPlayerMode = function(tab, mode, page)
    {
        if ( !$(tab).hasClass("loadingMode") )
        {

            $(tab).addClass("loadingMode");

            var realm = $("#realm_url").val();
            var name = $("#player_name").val();
            var guid = $("#player_guid").val();
            var expansion = $("#expansion_id").val();
            var map = $("#map_id").val();

            var container = $("#"+mode);
            $(container).html("<div class=\"encounters_loading\"><div class=\"loader\" style=\"display:block\"></div></div>");

            $.ajax({
                type: "GET",
                url: URL_WEBSITE + "/player/" + realm +  "/" + name + "/" + guid + "/" + mode + "/" + expansion + "/" + map + "?page=" + page,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response)
                {
                    $(container).html(response);
                    $(container).find(".pagination a").click(function(e)
                    {
                        e.preventDefault();
                        var url = new URL($(this).attr("href"));
                        var page = url.searchParams.get("page");
                        $(tab).removeClass("loadingMode");
                        loadPlayerMode(tab, mode, page);
                    });
                    UpdateTimes();

                    if ( mode === "top" ) {
                        container = $(".map-difficulty.active").find(".ajax-map-difficulty");
                        loadPlayerMapDifficulty(container, realm, name, guid, mode, expansion, map);
                        $(".map-difficulty-tab").on("click",function(){
                            if ( $(this).hasClass("unLoaded") ) {
                                $(this).removeClass("unLoaded");
                                var id = $(this).find("a").attr("href");
                                loadPlayerMapDifficulty($(id).find(".ajax-map-difficulty"), realm, name, guid, mode, expansion, map)
                            }
                        });
                    }
                }
            });

        }
    };

    var loadMapDifficultyMode = function(container,data, mode) {
        data += ("&mode_id="+mode);
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
                $(container).parent().html(replaceTranslations(response["view"]));
                UpdateTimes();
            }
        });
    };

    var listenForDifficultyMode = function(difficulty,data) {
        var modeSaved = getCookie("modeSaved3");
        if ( modeSaved !== "" )
        {
            $("#difficulty-form-response .modePanel, #difficulty-form-response .tab-pane").removeClass("active loaded");
            $("#modePanel" + modeSaved + "-" + difficulty + ", #" + modeSaved + "-" + difficulty).addClass("active loaded");
        }
        var container = $(".map-difficulty-mode-"+difficulty+".active").find(".difficulty-mode-loading-container-"+difficulty);
        loadMapDifficultyMode(container, data, $(".map-difficulty-mode-"+difficulty+".active").data("mode"));
        $(".map-difficulty-mode-tab-"+difficulty).on("click",function(){
            setCookie("modeSaved3",$(this).data("mode"));
            if ( !$(this).hasClass("loaded") ) {
                $(this).addClass("loaded");
                var id = $(this).find("a").attr("href");
                loadMapDifficultyMode($(id).find(".difficulty-mode-loading-container-"+difficulty), data, $(this).data("mode"));
            }
        });
    };

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
        setCookie("modeSaved2","");
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
                $(container).html(replaceTranslations(response["view"]));

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

    var firstPlayerSubmit = true;
    $("#pve-player-form").submit(function(e){
        e.preventDefault();
        var container = $("#map-loading-container");
        container.html("");
        var loader = $(".encounters_loading");
        loader.show();
        $("#pve-player-filter").attr("disabled",true);
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

        var realm = $("#realm_url").val();
        var name = $("#player_name").val();
        var guid = $("#player_guid").val();

        firstPlayerSubmit = false;
        data = $.param(data);
        setCookie("modeSaved2","");
        $.ajax({
            type: "POST",
            url: URL_WEBSITE + "/player/" + realm +  "/" + name + "/" + guid,
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response)
            {
                response = $.parseJSON(response);
                $(loader).hide();
                $(container).html(replaceTranslations(response["view"]));

                //prevState = window.location.href;
                //history.pushState(null, '', response["url"] + window.location.hash);

                $(".selectpicker").selectpicker();
                $(".bossName select[name='map_id']").on("change",function(){
                    var val = $(this).val();
                    if ( val && val > 0 ) {
                        firstPlayerSubmit = true;
                        $("#pve-player-form select[name='map_id']").val(val);
                        $("#pve-player-form").submit();
                    }
                });
                $("#pve-player-filter").attr("disabled",false);



                $("#player-response-form").find(".modePanel").each(function(){
                    var tab = $(this);
                    if ($(tab).hasClass("active"))
                    {
                        loadPlayerMode(tab, $(tab).data("mode"),1);
                    }
                    else
                    {
                        $(this).on("click",function(){
                            loadPlayerMode(tab, $(tab).data("mode"),1);
                        });
                    }
                });
            }
        });
    });
    if ( $("#pve-ladder-form") )
    {
        $("#pve-ladder-form").submit();
    }
    if ( $("#pve-player-form") )
    {
        $("#pve-player-form").submit();
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


    var loadEncounterDataMode = function(tab, id, mode) {
        if ( !$(tab).hasClass("loadingMode") ) {

            $(tab).addClass("loadingMode");
            var container = $("#"+mode);
            $(container).html("<div class=\"encounters_loading\"><div class=\"loader\" style=\"display:block\"></div></div>");
            $.ajax({
                type: "GET",
                url: URL_WEBSITE + "/encounter/MODE/" + id + "/" + mode,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $(container).html(replaceTranslations(response));
                }
            });
        }
    };

    $("#encounter-tabs").find("li").each(function(){
        var tab = $(this);
        var mode = $(tab).data("mode");
        var id = $(tab).data("id");
        if ($(tab).hasClass("active"))
        {
            loadEncounterDataMode(tab, id, mode);
        }
        else
        {
            $(this).on("click",function(){
                loadEncounterDataMode(tab, id, mode);
            });
        }
    });

    $("#gdkp-apply-form").submit(function(e){
        $("#gdkp-apply-form-submit").remove();
    });

    String.prototype.replaceAll = function(search, replace) {
        if (replace === undefined) {
            return this.toString();
        }
        return this.split(search).join(replace);
    };

    var replaceTranslations = function(html) {
        for ( var key in REPLACE_TRANSLATIONS ) {
            html = html.replaceAll(key, REPLACE_TRANSLATIONS[key]);
        }
        return html;
    }
});