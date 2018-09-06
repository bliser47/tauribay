<?php

namespace TauriBay;

use TauriBay\Tauri\WowInstance;
use Illuminate\Database\Eloquent\Model;
use DB;

class GdkpTrade extends Model
{
    public static function GetSimmilar($_name,$_text)
    {
        $traderTrades = GdkpTrade::where('name',$_name)->get();
        foreach ( $traderTrades as $trade )
        {
            similar_text($trade->text, $_text, $sim);
            $trade->simmilarity = $sim;
        }

        $traderTradesArray = $traderTrades->toArray();

        usort($traderTradesArray,function($a,$b)
        {
            return $a["simmilarity"] < $b["simmilarity"];
        });


        if ( count($traderTradesArray) > 0 && $traderTradesArray[0]["simmilarity"] > 50 ) {
            return $traderTradesArray[0];
        }
        return false;
    }

    public static function GetTrades($_request) {

        $gdkpTrades = DB::table('gdkp_trades')->orderBy('updated_at','desc');

        if ( $_request->has("filter") ) {

            // 1. Faction filter
            if ($_request->has('alliance') || $_request->has('horde') || $_request->has('ismeretlen')) {
                $factions = array();
                if ($_request->has('alliance')) {
                    array_push($factions, 2);
                }
                if ($_request->has('horde')) {
                    array_push($factions, 1);
                }
                if ($_request->has('ismeretlen')) {
                    array_push($factions, 3);
                }
                $gdkpTrades = $gdkpTrades->whereIn('faction', $factions);
            }

            $instances = [];
            $instanceNames = WowInstance::WOW_INSTANCE_SHORT_NAMES;
            foreach ($instanceNames as $instanceId => $instanceName) {
                if ($_request->has($instanceName)) {
                    array_push($instances, $instanceId);
                }
            }
            if ( count($instances) > 0 ) {
                $gdkpTrades = $gdkpTrades->whereIn('instance', $instances);
            }
        }

        return $gdkpTrades;

    }
}
