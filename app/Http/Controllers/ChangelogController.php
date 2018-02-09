<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ChangelogController extends Controller
{
    public function ShowChanges(Request $_request)
    {
        return view("changelog");
    }
}
