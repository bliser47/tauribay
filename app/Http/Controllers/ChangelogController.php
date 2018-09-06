<?php

namespace TauriBay\Http\Controllers;

use Illuminate\Http\Request;

use TauriBay\Http\Requests;

class ChangelogController extends Controller
{
    public function ShowChanges(Request $_request)
    {
        return view("changelog");
    }
}
