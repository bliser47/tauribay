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
        required: "Kötelező megadni.",
        maxlength: $.validator.format( "Legfeljebb {0} karakter hosszú legyen." ),
        minlength: $.validator.format( "Legalább {0} karakter hosszú legyen." ),
        rangelength: $.validator.format( "Legalább {0} és legfeljebb {1} karakter hosszú legyen." ),
        email: "Érvényes e-mail címnek kell lennie.",
        url: "Érvényes URL-nek kell lennie.",
        date: "Dátumnak kell lennie.",
        number: "Számnak kell lennie.",
        digits: "Csak számjegyek lehetnek.",
        equalTo: "Meg kell egyeznie a két értéknek.",
        range: $.validator.format( "{0} és {1} közé kell esnie." ),
        max: $.validator.format( "Nem lehet nagyobb, mint {0}." ),
        min: $.validator.format( "Nem lehet kisebb, mint {0}." ),
        creditcard: "Érvényes hitelkártyaszámnak kell lennie.",
        remote: "Kérem javítsa ki ezt a mezőt.",
        dateISO: "Kérem írjon be egy érvényes dátumot (ISO)."
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

        var url = "http://www.tauribay.hu/ilvl";

        $("#newcharacter-form").hide();
        $(".loader").css("display","block");

        $.ajax({
            type: "POST",
            url: url,
            data: $("#newcharacter-form").serialize(),
            success: function(data)
            {
                $(".loader").css("display","none");
                $("#newcharacter-form").show();
            }
        });

        e.preventDefault();
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


    function UpdateTimes() {
        $(".time").each(function () {
            var time = $(this).data("time");
            var t = time.split(/[- :]/);
            var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
            var now = new Date();
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

            $(this).html(passed);

        });
    }

    UpdateTimes();
});