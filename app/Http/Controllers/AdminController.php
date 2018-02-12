<?php

namespace App\Http\Controllers;

use App\Trader;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;

class AdminController extends Controller
{
    public function ShowLowLevels()
    {
        $lowLevels = DB::table('traders')->whereNull('faction')->get();
        return view("low_levels")->with("lowLevels",$lowLevels);
    }

    public function UpdateLowLevel(Request $_request)
    {
        if ( $_request->has("name") )
        {
            $name = $_request->get('name');
            Trader::where('name',$name)->update(array(
                "faction" => $_request->get('faction'),
                "level" => $_request->get('level'),
                "race" => $_request->get('race'),
                "class" => $_request->get('class'),
                "updated_at" => Carbon::now()
             ));
        }
        return $this->ShowLowLevels();
    }
}
