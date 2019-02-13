<?php

namespace TauriBay\Http\Controllers;

use TauriBay\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use Image;
use TauriBay\Realm;
use Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $adIntents = array(
            1 => __("Eladó"),
            2 => __("Csere"),
            3 => __("Eladó és csere"),
            4 => __("Vétel")
        );

        return view('home', array(
            'user' => Auth::user(),
            'realms' => Realm::REALMS,
            'realmsShort' => Realm::REALMS_SHORT,
            'adIntents' => $adIntents
        ));
    }


     public function ChangeAvatar(Request $_request)
     {
        $user = Auth::user();
        if($_request->hasFile('avatar')){
            $avatar = $_request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
            Image::make($avatar)->resize(300, 300)->save( public_path('/uploads/avatars/' . $filename ) );

            $user->avatar = $filename;
            $user->save();
        }
        return redirect()->back()->with('user',$user);
    }


     public function ChangePassword(Request $_request)
     {
        $validator = Validator::make($_request->all(), [
             'password' => 'required|confirmed',
             'password_confirmation' => 'required|string|min:6',
        ]);

        if ( !$validator->fails() )
        {
            $user = Auth::user();
            $user->password = bcrypt($_request->get('password'));
            $user->save();

            return redirect()->back()->with('passwordSuccess', 'true');
        }
        else
        {
            return redirect()->back()->with('passwordErrors',$validator->errors());
        }
    }
}
