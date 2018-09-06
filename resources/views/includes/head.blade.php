<title>TauriBay</title>

<link rel="shortcut icon" href="{{ URL::asset('favicon.ico') }}" type="image/x-icon">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
<link href='https://fonts.googleapis.com/css?family=Play:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">

<link rel="stylesheet" href="{{ URL::asset('css/awesome-bootstrap-checkbox.css') }}"/>
<link rel="stylesheet" href="{{ URL::asset('css/style.css?v=20') }}"/>
<link rel="stylesheet" href="{{ URL::asset('css/responsive.css') }}"/>

<script>
    var TIME_LOCAL_DAY = "{{ __("n") }}";
    var TIME_LOCAL_HOUR = "{{ __("ó") }}";
    var TIME_LOCAL_MINUTE = "{{ __("p") }}";
    var TIME_LOCAL_SECOND = "{{ __("mp") }}";

    var VALIDATION_LOCAL_REQUIRED = "{{ __("Kötelező megadni.") }}";
    var VALIDATION_LOCAL_MAXLENGTH = "{{ __("Legfeljebb {0} karakter hosszú legyen.") }}";
    var VALIDATION_LOCAL_MINLENGTH = "{{ __("Legalább {0} karakter hosszú legyen.") }}";
    var VALIDATION_LOCAL_RANGELENGTH = "{{ __("A számnak {0} és {1} között kell lennie.") }}";
    var VALIDATION_LOCAL_EMAIL = "{{ __("Érvényes e-mail címnek kell lennie.") }}";
    var VALIDATION_LOCAL_URL = "{{ __("Érvényes URL-nek kell lennie.") }}";
    var VALIDATION_LOCAL_DATE = "{{ __("Dátumnak kell lennie.") }}";
    var VALIDATION_LOCAL_NUMBER = "{{ __("Számnak kell lennie.") }}";
    var VALIDATION_LOCAL_DIGIT = "{{ __("Csak számjegyek lehetnek.") }}";
    var VALIDATION_LOCAL_EQUAL = "{{ __("Meg kell egyeznie a két értéknek.") }}";
    var VALIDATION_LOCAL_RANGE = "{{ __("{0} és {1} közé kell esnie.") }}";
    var VALIDATION_LOCAL_MIN = "{{ __("Nem lehet nagyobb, mint {0}.") }}";
    var VALIDATION_LOCAL_MAX = "{{ __("Nem lehet kisebb, mint {0}.") }}";
    var VALIDATION_LOCAL_CREDITCARD = "{{ __("Érvényes hitelkártyaszámnak kell lennie.") }}";
    var VALIDATION_LOCAL_REMOTE = "{{ __("Kérem javítsa ki ezt a mezőt.") }}";
    var VALIDATION_LOCAL_DATEISO = "{{ __("Kérem írjon be egy érvényes dátumot (ISO).") }}";
</script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.js"></script>


<script type="text/javascript" src="{{ URL::asset('js/responsive-paginate.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/main.js?v=27') }}"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>

<meta name="google" content="notranslate">
<meta name="viewport" content="width=device-width, initial-scale=1">

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-30988835-1', 'auto');
    ga('send', 'pageview');

</script>