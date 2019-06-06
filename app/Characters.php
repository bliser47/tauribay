<?php

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;
use DB;

class Characters extends Model
{
    public static function addEncounter($character, $member) {
        $characterEncounter = CharacterEncounters::where("character_id","=",$character->id)->where("encounter_member_id","=",$member->id)->first();
        if ( $characterEncounter == null ) {
            $characterEncounter = new CharacterEncounters;
            $characterEncounter->character_id = $character->id;
            $characterEncounter->encounter_member_id = $member->id;
            $characterEncounter->save();
        }
    }

    public static function GetTopItemLevels($_request)
    {
        $orderBy = 'ilvl';
        if ( $_request->has('sort') )
        {
            $orderBy = $_request->get('sort');
        }
        if ( $orderBy == 'ilvl' )
        {
            $characters = DB::table('characters')->where('name','NOT LIKE','M#%')->where('ilvl','>=',480)->orderBy($orderBy, 'desc')->orderBy('name', 'asc');
        }
        else
        {
            $characters = DB::table('characters')->where('name','NOT LIKE','M#%')->where('achievement_points','>=',10000)->orderBy($orderBy, 'desc')->orderBy('name', 'asc');
        }
        //$characters = $characters->orderBy("guid","desc")->groupBy(array("realm","name"));
        if ( $_request->has("filter") ) {
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
                $characters = $characters->whereIn('faction', $factions);
            }

            $classes = [];
            $classNames = array("karakter", "warrior", "paladin", "hunter", "rogue", "priest", "dk", "shaman", "mage", "warlock",  "monk", "druid");
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
                    array_push($realms, Realm::TAURI);
                }
                if ($_request->has('wod')) {
                    array_push($realms, Realm::WOD);
                }
                if ($_request->has('evermoon')) {
                    array_push($realms, Realm::EVERMOON);
                }
                $characters = $characters->whereIn('realm', $realms);
            }


            if ($_request->has('search')) {
                $characters = $characters->where('name', 'LIKE', '%' . ucfirst(strtolower($_request->get('search'))) . '%');
            }
        }

        return $characters;
    }
}
