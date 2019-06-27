<?php

namespace TauriBay\Http\Controllers;

use Illuminate\Http\Request;
use TauriBay\Http\Requests;

class InstagramController extends Controller
{
    public function index(Request $_request)
    {
        return view("instagram/index");
    }
}
