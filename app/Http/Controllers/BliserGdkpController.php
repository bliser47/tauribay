<?php

namespace TauriBay\Http\Controllers;

use Illuminate\Http\Request;
use TauriBay\Http\Requests;
use TauriBay\Tauri;

class BliserGdkpController extends Controller
{
    public function index(Request $_request)
    {
        return view("gdkp");
    }
}
