<?php

namespace TauriBay\Http\Controllers\Auth;

use http\Env\Request;
use TauriBay\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';


    /**
     * @param $request
     * @return string
     */
    public function redirectTo()
    {
        if (!empty($_POST['redirectTo'])) {
            $this->redirectTo = $_POST['redirectTo'];
        }

        return $this->redirectTo ?? '/home';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['language','guest'])->except('logout');
    }
}
