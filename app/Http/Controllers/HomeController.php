<?php

namespace TauriBay\Http\Controllers;

use TauriBay\AuthorizedCharacter;
use TauriBay\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use Image;
use TauriBay\Realm;
use TauriBay\Tauri\CharacterClasses;
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

        $user = Auth::user();
        $characterClasses = CharacterClasses::CHARACTER_CLASS_NAMES;
        $authorizedCharacters = AuthorizedCharacter::where("user_id","=",$user->id)->leftJoin("characters","characters.id","=","authorized_characters.character_id")
        ->select(array(
            "characters.id as id",
            "characters.class as class",
            "characters.realm as realm",
            "characters.name as name",
            "authorized_characters.updated_at as updated_at"
        ))->get();
        $authorizationLifeTimeInHours = 168; // 1 week

        return view('home', array(
            'user' => $user,
            'realms' => Realm::REALMS,
            'realmsShort' => Realm::REALMS_SHORT,
            'adIntents' => $adIntents,
            'authorizedCharacters' => $authorizedCharacters,
            'characterClasses' => $characterClasses,
            'authorizationLifeTimeInHours' => $authorizationLifeTimeInHours
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
