<!doctype html>
<html>
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
            </footer>
        </div>
    </body>
</html>