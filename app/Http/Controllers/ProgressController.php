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
        $guilds = DB::table('guild_progresses')
            ->leftJoin('guilds', 'guild_progresses.guild_id', '=', 'guilds.id')
            ->where("guild_progresses.map_id", "=", 1098)->whereIn("guild_progresses.difficulty_id",array(5,6))
            ->where("guild_progresses.progress", ">", 0)
            ->orderBy("guild_progresses.progress", "desc")
            ->orderBy("guild_progresses.clear_time")->get();

        if ( $_request->has("filter") ) {

            // 1. Faction filter
            if ($_request->has('alliance') || $_request->has('horde') || $_request->has('ismeretlen')) {
                $factions = array();
                if ($_request->has('alliance')) {
                    array_push($factions, 0);
                }
                if ($_request->has('horde')) {
                    array_push($factions, 1);
                }
                if ($_request->has('ismeretlen')) {
                    array_push($factions, 3);
                }
                $guilds = $guilds->whereIn('faction', $factions);
            }

            // 2. Realm filter
            if ($_request->has('tauri') || $_request->has('wod') || $_request->has('evermoon')) {
                $realms = array();
                if ($_request->has('tauri')) {
                    array_push($realms, 0);
                }
                if ($_request->has('wod')) {
                    array_push($realms, 1);
                }
                if ($_request->has('evermoon')) {
                    array_push($realms, 2);
                }
                $guilds = $guilds->whereIn('realm', $realms);
            }

            // 2. Size filter
            if ($_request->has('difficulty10') || $_request->has('difficulty25') ) {
                $difficulties = array();
                if ($_request->has('difficulty10')) {
                    array_push($difficulties, 5);
                }
                if ($_request->has('difficulty25')) {
                    array_push($difficulties, 6);
                }
                $guilds = $guilds->whereIn('difficulty_id', $difficulties);
            }
        }

        $shortRealms = self::SHORT_REALM_NAMES;
        $longRealms = self::REALM_NAMES;
        return view("progress_guild", compact("guilds", 'shortRealms', 'longRealms'));
    }


    public function updateGuildProgress(Request $_request)
    {
        if ( $_request->has("name") && $_request->has("realm") )
        {
            return response()->json(GuildProgress::reCalculateProgressionFromNameAndRealm($_request->get("name"),$_request->get("realm")));
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
