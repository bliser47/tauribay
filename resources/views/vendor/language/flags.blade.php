@foreach (language()->allowed() as $code => $name)
    @if ( language()->flag()->name != $name )
        <li>
            <a href="{{ language()->back($code) }}">
                <img src="{{ asset('img/flags/'. language()->country($code) .'.png') }}" alt="{{ $name }}" width="{{ config('language.flags.width') }}" />
                {!! $name !!}
            </a>
        </li>
    @endif
@endforeach