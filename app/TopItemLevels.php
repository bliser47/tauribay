<?php

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;
use DB;

class TopItemLevels extends Model
{
    public static function GetTopItemLevels($_request)
    {

        $characters = DB::table('top_item_levels')->where('ilvl','>=',480)->orderBy('ilvl', 'desc');

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
                $characters = $characters->whereIn('faction', $factions);
            }

            $classes = [];
            $classNames = array("karakter", "warrior", "paladin", "hunter", "rogue", "priest", "dk", "shaman", "mage", "warlock", "druid", "monk");
            foreach ($classNames as $classId => $className) {
                if ($_request->has($className)) {
                    array_push($classes, $classId);
                }
            }

            if (count($classes) > 0) {
                $characters = $characters->whereIn('class', $classes);
            }

            if ($_request->has('search')) {
                $characters = $characters->where('name', 'LIKE', '%' . $_request->get('search') . '%');
            }
        }

        return $characters;
    }
}
