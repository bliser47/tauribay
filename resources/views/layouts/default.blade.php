<!doctype html>
<html lang="{{ App::getLocale() }}">
    <head>
        @include('includes.head')
    </head>
    <body>
        <div class="loadWrapper">
            <div class="loader pageLoader"></div>
        </div>
        <div class="wrapper">
            <header class="sticky">
                @include('includes.header')
            </header>
            <div class="container">
                @yield('content')
            </div>
            <footer>
                @include('includes.footer')
                @yield('page.footer');
            </footer>
        </div>
    </body>
</html>