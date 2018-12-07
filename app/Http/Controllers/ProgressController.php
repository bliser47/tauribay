<?php

namespace TauriBay\Http\Controllers;

use TauriBay\Encounter;
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

    public function index(Request $_request)
    {
        $data = DB::table('encounters')->where('created_at','>',Carbon::now()->subDays(14))->orderBy('killtime','desc')->paginate(16);
        $shortRealms = self::REALM_NAMES;
        return view("progress", compact("data", 'shortRealms'));
    }


    public function updateRaids(Request $_request)
    {
        $api = new Tauri\ApiClient();
        $realmId = 1;//$_request->has("data") ? $_request->get("data") : 2;
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
