<?php

namespace TauriBay\Http\Controllers;

use TauriBay\Encounter;
use TauriBay\GuildProgress;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use TauriBay\Http\Requests;
use TauriBay\Tauri;
use DB;
use Carbon\Carbon;

class ProgressController extends Controller
{
    const REALM_NAMES = array(
        0 => "[HU] Tauri WoW Server",
        1 => "[HU] Warriors of Darkness",
        2 => "[EN] Evermoon"
    );

    const SHORT_REALM_NAMES = array(
        0 => "Tauri",
        1 => "WoD",
        2 => "Evermoon"
    );


    public function index(Request $_request)
    {
        $data = DB::table('encounters')->where('created_at','>',Carbon::now()->subDays(14))->orderBy('killtime','desc')->paginate(16);
        $shortRealms = self::SHORT_REALM_NAMES;
        return view("progress", compact("data", 'shortRealms'));
    }

    public function guild(Request $_request)
    {
        $guilds = DB::table('guilds')->get();
        foreach ($guilds as $guild )
        {
            $progress = GuildProgress::getProgression($guild->id);;
            $guild->progress = $progress["progress"];
            $guild->progressText = $progress["progress"] . "/" . $progress["total"];
        }
        $guilds = $guilds->sortByDesc(function ($guild, $key) {
            return $guild->progress;
        });
        $shortRealms = self::SHORT_REALM_NAMES;
        return view("progress_guild", compact("guilds", 'shortRealms'));
    }


    public function updateGuildProgress(Request $_request)
    {
        if ( $_request->has("name") && $_request->has("realm") )
        {
            return response()->json([
                "progress" =>GuildProgress::reCalculateProgressionFromNameAndRealm($_request->get("name"),$_request->get("realm"))
            ]);
        }
        return "";
    }

    public function updateGuildProgressAll(Request $_request)
    {
        GuildProgress::reCalculateProgressionForAll();
    }


    public function updateRaids(Request $_request)
    {
        $api = new Tauri\ApiClient();
        $realmId = 2;//$_request->has("data") ? $_request->get("data") : 2;
        $realmName = self::REALM_NAMES[$realmId];

        $latestRaids = $api->getRaidLast($realmName);

        $start = "\"response\":{\"logs\":[{";
        $startPos = strpos ($latestRaids,$start);
        $end = "]}}";
        $endPos = strrpos($latestRaids,$end);
        $delimiter = "},{";

        $logs = substr($latestRaids,$startPos+strlen($start),$endPos);
        $logs = explode($delimiter,$logs);

        $result = array();
        for ( $i = 0 ; $i < count($logs) ; ++$i)
        {
            $encounter = Encounter::store(json_decode( "{" . $logs[$i]."}" , true), $realmId);
            $result[] = $encounter;
            if ( !$encounter["result"] )
            {
                break;
            }
        }
        return json_encode($result);
    }
}
