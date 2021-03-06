<?php

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class CharacterTrade extends Model
{
    public static function GetSimmilar($_name,$_text)
    {
        $traderTrades = CharacterTrade::where('name',$_name)->get();
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

        $characterTrades = DB::table('character_trades')->where('created_at','>',Carbon::now()->subDays(30))->orderBy('updated_at','desc');

        if ( $_request->has("filter") ) {

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
                $characterTrades = $characterTrades->whereIn('realm_id', $realms);
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
                $characterTrades = $characterTrades->whereIn('faction', $factions);
            }

            // 2. Intent filter
            if ($_request->has('elado') || $_request->has('vetel') || $_request->has('csere')) {
                $intent = [];
                if ($_request->has('elado')) {
                    array_push($intent, 0);
                    array_push($intent, 1);
                }
                if ($_request->has('vetel')) {
                    array_push($intent, 2);
                } else if ($_request->has('csere')) {
                    array_push($intent, 0);
                    array_push($intent, 3);
                }
                $characterTrades = $characterTrades->whereIn('intent', $intent);
            }

            $classes = [];
            $classNames = array("karakter", "warrior", "paladin", "hunter", "rogue", "priest", "dk", "shaman", "mage", "warlock",  "monk", "druid");
            foreach ($classNames as $classId => $className) {
                if ($_request->has($className)) {
                    array_push($classes, $classId);
                }
            }

            if (count($classes) > 0) {
                $characterTrades = $characterTrades->whereIn('class', $classes);
            }

            if ($_request->has('search')) {
                $characterTrades = $characterTrades->where('text', 'LIKE', '%' . $_request->get('search') . '%');
            }
        }

        return $characterTrades;

    }
}
