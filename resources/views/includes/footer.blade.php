<link rel="shortcut icon" href="{{ URL::asset('favicon.ico') }}" type="image/x-icon">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
<link href='https://fonts.googleapis.com/css?family=Play:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{ URL::asset('css/awesome-bootstrap-checkbox.css') }}"/>
<link rel="stylesheet" href="{{ URL::asset('css/style.css?v=187') }}"/>
<link rel="stylesheet" href="{{ URL::asset('css/responsive.css?v=6') }}"/>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
<script>
    var URL_WEBSITE = "{{ URL::to('/') }}";

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
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('js/responsive-paginate.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/main.js?v=185') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/power.js?v=25') }}"></script>
@yield('pagespecificscripts')
<script>
    $(window).on('load', function() {
        $(".loadWrapper").fadeTo(500,0);
        $("<img src=\"{{ URL::asset("img/back.jpg") }}\">").load(function() {
            $(".wrapper").addClass("backLoaded");
            $(".wrapper").fadeTo(500,1);
        });
    });
</script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-30988835-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-30988835-1');
</script>
<script>
    window['_fs_debug'] = false;
    window['_fs_host'] = 'fullstory.com';
    window['_fs_org'] = 'G7RWB';
    window['_fs_namespace'] = 'FS';
    (function(m,n,e,t,l,o,g,y){
        if (e in m) {if(m.console && m.console.log) { m.console.log('FullStory namespace conflict. Please set window["_fs_namespace"].');} return;}
        g=m[e]=function(a,b,s){g.q?g.q.push([a,b,s]):g._api(a,b,s);};g.q=[];
        o=n.createElement(t);o.async=1;o.src='https://'+_fs_host+'/s/fs.js';
        y=n.getElementsByTagName(t)[0];y.parentNode.insertBefore(o,y);
        g.identify=function(i,v,s){g(l,{uid:i},s);if(v)g(l,v,s)};g.setUserVars=function(v,s){g(l,v,s)};g.event=function(i,v,s){g('event',{n:i,p:v},s)};
        g.shutdown=function(){g("rec",!1)};g.restart=function(){g("rec",!0)};
        g.consent=function(a){g("consent",!arguments.length||a)};
        g.identifyAccount=function(i,v){o='account';v=v||{};v.acctId=i;g(o,v)};
        g.clearUserCookie=function(){};
    })(window,document,window['_fs_namespace'],'script','user');
</script>