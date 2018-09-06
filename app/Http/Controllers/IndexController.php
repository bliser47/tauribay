<?php

namespace TauriBay\Http\Controllers;


use Illuminate\Http\Request;
use TauriBay\Http\Requests;

class IndexController extends Controller
{
    public function Start(Request $_request)
    {
        return view('index');
    }

}
