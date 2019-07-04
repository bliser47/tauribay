<?php

namespace TauriBay\Http\Controllers;

use Illuminate\Http\Request;
use TauriBay\Http\Requests;
use TauriBay\Photo;
use Carbon\Carbon;
use Auth;
use Image;

class InstagramController extends Controller
{

    const MAX_PHOTOS = 1;

    public function index(Request $_request)
    {
        $user = Auth::user();
        $photosToday = 0;
        if ( $user ) {
            $photosToday = Photo::where("user_id","=",$user->id)->where("created_at",Carbon::today())->count();
        }
        $photos = Photo::where("photos.created_at",Carbon::today())->leftJoin("users","users.id","=","photos.user_id")->get();
        $_request->merge(array('redirectTo' => "/insta#upload"));
        return view("instagram/index",compact("photosToday","photos"));
    }

    public function photo(Request $request, $id) {
        $photo = Photo::where("id","=",$id)->first();
        return view("instagram/photo",compact("photo"));
    }

    public function delete(Request $request) {

    }

    public function upload(Request $_request)
    {
        $user = Auth::user();
        if($user && $_request->hasFile('photo')){
            $photosToday = Photo::where("user_id","=",$user->id)->where("created_at",Carbon::today())->count();
            if ( $photosToday < self::MAX_PHOTOS ) {
                $photo = $_request->file('photo');
                $filename = time() . '.' . $photo->getClientOriginalExtension();
                $filenameSmall = time() . '_small.' . $photo->getClientOriginalExtension() ;
                Image::make($photo)->save( public_path('/uploads/instagram/' . $filename ) );
                Image::make($photo)->fit(64,64)->save( public_path('/uploads/instagram/' . $filenameSmall ) );


                $p = new Photo;
                $p->user_id = $user->id;
                $p->name = $filename;
                $p->nameSmall = $filenameSmall;
                $p->save();

                return redirect()->back()->with('user',$user);
            } else {
                echo "Can't upload more photos";
            }
        } else {
            if ( !$user ) {
                echo "Not logged in";
            } else {
                echo "No photo found";
            }
        }
    }
}
