<div class="panel-body">
    @foreach ( $photos as $photo )
        <div class="photo-container">
            <a href="{{ URL::to("insta/photo/" . $photo->id) }}" class="instaPhoto">
                <img src="{{ URL::to("/uploads/instagram/" . $photo->nameSmall)}}" alt=""/>
            </a>
            <div class="col-md-12">
                <a href="">{{ $photo->email }}</a>
            </div>
        </div>
    @endforeach
</div>