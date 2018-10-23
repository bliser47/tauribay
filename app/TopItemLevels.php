<?php

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;
use DB;

class TopItemLevels extends Model
{
    public static function GetTopItemLevels($_request)
    {
        $orderBy = 'ilvl';
        if ( $_request->has('sort') )
        {
            $orderBy = $_request->get('sort');
        }
        if ( $orderBy == 'ilvl' )
        {
            $characters = DB::table('top_item_levels')->where('name','NOT LIKE','M#%')->where('ilvl','>=',480)->orderBy($orderBy, 'desc');
        }
        else
        {
            $characters = DB::table('top_item_levels')->where('name','NOT LIKE','M#%')->where('achievement_points','>=',10000)->orderBy($orderBy, 'desc');
        }
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
                $characters = $characters->whereIn('realm', $realms);
            }


            if ($_request->has('search')) {
                $characters = $characters->where('name', 'LIKE', '%' . ucfirst($_request->get('search')) . '%');
            }
        }

        return $characters;
    }
}
