$(function()
{
    $(".pagination").rPage();

    $(window).scroll(function(){
        var sticky = $('.sticky'),
            scroll = $(window).scrollTop();

        if (scroll >= 51) sticky.addClass('fixed');
        else if(scroll === 0) sticky.removeClass('fixed');
    });

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

                        var clearTime = parseInt(progress["clear_time"]).toString().toHHMMSS();

                        $(row).find(".guildClearTime").html(clearTime);

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
        var time    = hours+':'+minutes+':'+seconds;
        return time;
    }

    function UpdateTimes() {
        $(".time").each(function () {
            parseTime($(this));
        });
        $(".guildClearTime").each(function () {
            var time = $(this).html();
            if ( time.length > 0 ) {
                $(this).html((parseInt(time)).toString().toHHMMSS());
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
});