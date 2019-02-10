<?php
/**
 * Created by PhpStorm.
 * User: thoma
 * Date: 2/10/2019
 * Time: 9:54 AM
 */

namespace TauriBay\Http\Controllers;

use Illuminate\Http\Request;
use TauriBay\Defaults;
use TauriBay\Encounter;
use TauriBay\Loot;
use TauriBay\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;

class StatsController extends Controller
{
    public function index(Request $_request)
    {
        $cacheKey = "lootType" . http_build_query($_request->all()) . "_" . Lang::locale();;
        $cacheValue = Cache::get($cacheKey);
        if ( !$cacheValue ) {
            $totEncounterIds = Encounter::getMapEncountersIds(Defaults::EXPANSION_ID, Defaults::MAP_ID);
            $subClass = array(
                Item::LOOT_TYPE_PLATE, Item::LOOT_TYPE_MAIL, Item::LOOT_TYPE_LEATHER, Item::LOOT_TYPE_CLOTH
            );

            $lootsData = Loot::leftJoin("encounters", "encounters.id", "=", "loots.encounter_id")
                ->whereIn("encounters.encounter_id", $totEncounterIds)->leftJoin("items", "loots.item_id", "=", "items.id")
                ->whereIn("items.subclass", $subClass)->groupBy("items.subclass")->select(
                    DB::raw('count(items.subclass) as num')
                )->get();

            $loots = array();
            foreach ($lootsData as $data) {
                $loots[] = $data["num"];
            }


            $items =  Loot::leftJoin("encounters", "encounters.id", "=", "loots.encounter_id")
                ->whereIn("encounters.encounter_id", $totEncounterIds)->leftJoin("items", "loots.item_id", "=", "items.id")
                ->groupBy("items.item_id")->select(
                    DB::raw('encounters.encounter_id as encounter, encounters.difficulty_id as difficulty, items.name as name, items.item_id, count(items.item_id) as num')
                )->orderBy("num","DESC")->paginate(50);

            $itemsTotal = Loot::leftJoin("encounters", "encounters.id", "=", "loots.encounter_id")->whereIn("encounters.encounter_id", $totEncounterIds)->count();

            $itemsEncounter = Loot::leftJoin("encounters", "encounters.id", "=", "loots.encounter_id")->whereIn("encounters.encounter_id", $totEncounterIds)
                ->groupBy("encounters.encounter_id","encounters.difficulty_id")->select(
                    DB::raw('encounters.encounter_id, encounters.difficulty_id, count(*) as num')
                )->orderBy("num","DESC")->get();


            $bossItems = array();
            foreach ( $itemsEncounter as $itemEncounter )
            {
                if ( !array_key_exists($itemEncounter["encounter_id"], $bossItems) )
                {
                    $bossItems[$itemEncounter["encounter_id"]] = array();
                }
                $bossItems[$itemEncounter["encounter_id"]][$itemEncounter["difficulty_id"]]  = $itemEncounter->num;
            }

            $view = view("stats/loot", compact("loots", 'items', "itemsTotal","bossItems"));
            $cacheValue = $view->render();
            Cache::put($cacheKey, $cacheValue, 60); // 1 min
        }

        return $cacheValue;
    }
}