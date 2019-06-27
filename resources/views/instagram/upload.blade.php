@if (Auth::guest())
    <div class="col-md-12">
        @include('auth.login_content')
    </div>
@else

@endif