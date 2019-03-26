<link type="image/x-icon" rel="shortcut icon" href="{{ URL::asset('favicon.ico') }}">
<link type="text/css" rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href='https://fonts.googleapis.com/css?family=Play:400,700'>
<link type="text/css" rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
<link type="text/css" rel="stylesheet" href="{{ URL::asset('css/awesome-bootstrap-checkbox.css') }}"/>
<link type="text/css" rel="stylesheet" href="{{ URL::asset('css/style.css?v=273') }}"/>
<link type="text/css" rel="stylesheet" href="{{ URL::asset('css/responsive.css?v=16') }}"/>
<link type="text/css" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
<link type="text/css" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css" />
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

    var REPLACE_TRANSLATIONS = {
        HEADER_PLAYER : "{{ __("Játékos") }}",
        HEADER_DATE : "{{ __("Dátum") }}",
        HEADER_TIME : "{{ __("Idő") }}",
        HEADER_BEST_TIME : "{{ __("Legjobb idő") }}",
        CELL_NO_DATA : "{{ __("Nincs adat") }}",
    };

    var COOKIE_POLICY = "{{ __("Ez a weboldal a felhasználói élmény javítása, valamint a zavartalan működés biztosítása érdekében sütiket (cookie-kat) használ.") }}";
    var COOKIE_POLICY_OKAY = "{{ __("Elfogadom") }}";
    var COOKIE_POLICY_LEARN = "{{ __("Tudj meg többet") }}";
</script>
<script type="application/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="application/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script type="application/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
<script type="application/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.js"></script>
<script type="application/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
<script type="application/javascript" src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
<script type="application/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.js"></script>

<script type="application/javascript" src="{{ URL::asset('js/responsive-paginate.js') }}"></script>
<script type="application/javascript" src="{{ URL::asset('js/main.js?v=265') }}"></script>
<script type="application/javascript" src="{{ URL::asset('js/power.js?v=26') }}"></script>
<script type="application/javascript" src="{{ URL::asset('js/ads.js') }}"></script>
@yield('pagespecificscripts')
<script type="application/javascript">
    $(window).on('load', function() {
        if(!document.getElementById('IsdRpWLgMNqY')){
            document.getElementById('MEIGitqPUFDH').style.display='block';
        } else {
            document.getElementById('amazon').className = "amazon noblocker";
        }
        $(".loadWrapper").fadeTo(500,0);
        $("<img src=\"{{ URL::asset("img/back.jpg") }}\">").load(function() {
            var originalWidth = this.width;
            var originalHeight = this.height;
            $(".wrapper").prepend("<img class=\"backLoaded\" src=\"{{ URL::asset("img/back.jpg") }}\">").fadeTo(500,1)
            function resizeBackground() {
                var windowWidth = $(window).width();
                var height = $(window).height()+100;
                var width = (height * originalWidth)/originalHeight;
                if ( width < windowWidth )
                {
                    width = windowWidth;
                    height = (width * originalHeight)/originalWidth;
                }
                $("img.backLoaded").width(width).height(height).css("margin-left",width/-2);
            }
            $(window).resize(resizeBackground);
            resizeBackground();
            window.cookieconsent.initialise({
                "palette": {
                    "popup": {
                        "background": "rgba(0,0,0,0.85)",
                        "text": "#ffffff"
                    },
                    "button": {
                        "background": "#5cb85c",
                        "text": "#ffffff"
                    }

                },
                "content": {
                    "message": COOKIE_POLICY,
                    "dismiss": COOKIE_POLICY_OKAY,
                    "link": COOKIE_POLICY_LEARN
                },
                "cookie" : {
                    "domain" : ".tauribay.hu"
                }
            })
        });
    });
</script>
<script type="application/javascript" async src="https://www.googletagmanager.com/gtag/js?id=UA-30988835-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-30988835-1');
</script>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
    (adsbygoogle = window.adsbygoogle || []).push({
        google_ad_client: "ca-pub-6255828756943386",
        enable_page_level_ads: true
    });
</script>