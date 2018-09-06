$(function()
{

    $(".pagination").rPage();


    $(".checkbox-all-classes input").change(function(){
        $(".checkbox-class input").prop('checked', $(this).prop("checked"));
    });
    $(".checkbox-all-factions input").change(function(){
        $(".checkbox-faction input").prop('checked', $(this).prop("checked"));
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
            url: "https://tauribay.hu/ilvl",
            data: data,
            success: function(response)
            {
                if ( response.indexOf("Operation timed out") !== -1 )
                {
                    sendIlvlAjax(data);
                }
                else {
                    $(".loader").css("display", "none");
                    $("#newcharacter-form").show();
                }
            },
            error: function(xhr, textStatus, errorThrown){
                $(".loader").css("display", "none");
                $("#newcharacter-form").show();
            }
        });
    }
    
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


    function sendIlvlAjaxUpdate(form, data, row)
    {
        $(form).hide();
        $(form).parent().find(".update-loader").css("display","block");
        $.ajax({
            type: "POST",
            url: "https://tauribay.hu/ilvl",
            data: data,
            success: function(response)
            {
                if ( response.indexOf("Operation timed out") !== -1 )
                {
                    sendIlvlAjaxUpdate(data);
                }
                else
                {
                    if ( response.length )
                    {
                        $(row).html(response);
                        parseTime($(row).find(".time"));
                        $(row).find(".ilvlupdate-form").submit(function(e) {

                            sendIlvlAjaxUpdate($(this),$(this).serialize(), $(this).parent().parent());
                            e.preventDefault();
                        });
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

        sendIlvlAjaxUpdate($(this),$(this).serialize(), $(this).parent().parent());
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

    function UpdateTimes() {
        $(".time").each(function () {
            parseTime($(this));
        });
    }

    UpdateTimes();
});