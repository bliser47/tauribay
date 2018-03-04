<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class TopItemLevels extends Model
{
    public static function GetTopItemLevels($_request)
    {
        $characters = DB::table('top_item_levels')->groupBy('name','realm')->orderBy('ilvl', 'desc');

        /*
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
            $classNames = array("karakter", "warrior", "paladin", "hunter", "rogue", "priest", "dk", "shaman", "mage", "warlock", "druid", "monk");
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
        */

        return $characters;
    }
}
