<div class="panel-body">
    @foreach ( $photos as $photo )
        <a href="{{ URL::to("insta/photo/" . $photo->id) }}" class="instaPhoto">
            <img src="{{ URL::to("/uploads/instagram/" . $photo->nameSmall)}}" alt=""/>
        </a>
    @endforeach
</div>