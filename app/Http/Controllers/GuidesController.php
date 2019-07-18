<?php

namespace TauriBay\Http\Controllers;

use Illuminate\Http\Request;
use TauriBay\EncounterMember;
use TauriBay\Http\Requests;

class GuidesController extends Controller
{
    public function index(Request $request) {

        $classes = EncounterMember::getClasses();

        return view("guides/classes/all",compact("classes"));
    }
}