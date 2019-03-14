<?php

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;
use DB;

class CreditTrade extends Model
{
    const CREDIT_INTENT_NAMES = array(
        'Eladás', 'Vétel'
    );


    public static function GetSimmilar($_name,$_text)
    {
        $traderTrades = CreditTrade::where('name',$_name)->get();
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

        $creditTrades = DB::table('credit_trades')->orderBy('updated_at','desc');

        // 0. Realm filter
        if ($_request->has('tauri') || $_request->has('wod') || $_request->has('evermoon')) {
            $realms = array();
            if ($_request->has('tauri')) {
                array_push($realms, Realm::TAURI);
            }
            if ($_request->has('wod')) {
                array_push($realms, Realm::WOD);
            }
            if ($_request->has('evermoon')) {
                array_push($realms, Realm::EVERMOON);
            }
            $creditTrades = $creditTrades->whereIn('realm_id', $realms);
        }


        // 1. Faction filter
        if ($_request->has('alliance') || $_request->has('horde') || $_request->has('ismeretlen')) {
            $factions = array();
            if ($_request->has('alliance')) {
                array_push($factions, Faction::ALLIANCE);
            }
            if ($_request->has('horde')) {
                array_push($factions, Faction::HORDE);
            }
            if ($_request->has('ismeretlen')) {
                array_push($factions, Faction::NEUTRAL);
            }
            $creditTrades = $creditTrades->whereIn('faction', $factions);
        }

        // 2. Intent filter
        if ($_request->has('elado') || $_request->has('vetel')) {
            $intent = [];
            if ($_request->has('elado')) {
                array_push($intent, 0);
            }
            if ($_request->has('vetel')) {
                array_push($intent, 1);
            }
            $creditTrades = $creditTrades->whereIn('intent', $intent);
        }


        if ($_request->has('search')) {
            $creditTrades = $creditTrades->where('text', 'LIKE', '%' . $_request->get('search') . '%');
        }


        return $creditTrades;

    }
}
