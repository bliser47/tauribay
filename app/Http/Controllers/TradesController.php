<?php

namespace App\Http\Controllers;

use App\CharacterTrade;
use App\ParsedData;
use App\GdkpTrade;
use App\Tauri\GdkpIntent;
use App\Tauri\WowInstance;
use App\Tauri\WoWInstanceDifficulty;
use App\Tauri\WowInstanceSize;
use Illuminate\Http\Request;
use App\Tauri\CharacterClasses;
use App\Tauri\CharacterIntent;
use App\Http\Requests;
use DB;
use Carbon\Carbon;

class TradesController extends Controller
{
    public function ShowAll(Request $_request)
    {
        $trades = ParsedData::where('created_at','>',Carbon::now()->subHours(1))->where('faction','!=',0)->orderBy('created_at','desc')->paginate(16);
        return view('trades')->with(compact('trades'));
    }

    public function ShowCharacters(Request $_request)
    {
        
        $characterTrades = CharacterTrade::GetTrades($_request)->paginate(16);
        $characterFactions = array("Ismeretlen", "Horde", "Alliance");
        $characterIntents = CharacterIntent::CHARACTER_INTENT_NAMES;
        $characterClasses = CharacterClasses::CHARACTER_CLASS_NAMES;

        return view('characters')->with(compact('characterTrades','characterIntents','characterClasses','characterFactions'));
    }

    public function ShowGdkps(Request $_request)
    {
        $gdkpTrades = GdkpTrade::GetTrades($_request)->where('created_at','>',Carbon::now()->subDays(2))->paginate(16);
        $gdkpIntents = GdkpIntent::GDKP_INTENT_NAMES;
        $gdkpInstances = WowInstance::WOW_INSTANCE_NAMES;
        $gdkpInstanceSizes = WowInstanceSize::WOW_INSTANCE_SIZE_NAMES;
        $gdkpInstanceDifficulties = WoWInstanceDifficulty::WOW_INSTANCE_DIFFICULTY_NAMES;

        return view('gdkps')->with(compact('gdkpTrades','gdkpIntents','gdkpInstances','gdkpInstanceSizes','gdkpInstanceDifficulties'));
    }

    public function ShowCredits(Request $_request)
    {
        
    }
}
