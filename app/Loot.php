<?php
/**
 * Created by PhpStorm.
 * User: thoma
 * Date: 2/8/2019
 * Time: 11:13 AM
 */

namespace TauriBay;


use Illuminate\Database\Eloquent\Model;
use TauriBay\Tauri\ApiClient;

class Loot extends Model
{
    public static function processItems($encounter, $items, $api)
    {
        $loots = [];
        $requestItemData = [];
        for ( $i = 0 ; $i < count($items) ; ++$i )
        {
            $itemData = $items[$i];
            $itemId = $itemData["itemid"];
            $item = Item::where("item_id", "=", $itemId)->first();
            if ( $item == null )
            {
                $requestItemData[$itemId] = array(
                    "count" => $itemData["count"],
                    "random_prop" => $itemData["random_prop"],
                    "random_suffix" => $itemData["random_suffix"],
                );
            }
            else
            {
                $loots[] = array(
                    "item" => $item,
                    "count" => $itemData["count"],
                    "random_prop" => $itemData["random_prop"],
                    "random_suffix" => $itemData["random_suffix"]
                );
            }
        }
        if ( count($requestItemData) > 0 )
        {
            $itemData = $api->getItemTooltipDataByEntry(Realm::REALMS[$encounter->realm_id], array_keys($requestItemData));
            $itemData = $itemData["response"];
            foreach ( $itemData as $key => $itemObject )
            {
                if ( array_key_exists(intval($key), $requestItemData) )
                {
                    $item = new Item;
                    $item->item_id = $itemObject["ID"];
                    $item->name = $itemObject["name"];
                    $item->quality = $itemObject["Quality"];
                    $item->ilvl = $itemObject["ItemLevel"];
                    $item->icon = $itemObject["m_inventoryIcon"];
                    $item->class = $itemObject["_Class"];
                    $item->subclass = $itemObject["_SubClass"];
                    $item->heroic = $itemObject["is_heroic"];
                    $item->description = $itemObject["itemnamedescription"];
                    $item->inventory_type = $itemObject["InventoryType"];
                    $item->save();

                    $loots[] = array(
                        "item" => $item,
                        "count" => $requestItemData[$key]["count"],
                        "random_prop" => $requestItemData[$key]["random_prop"],
                        "random_suffix" => $requestItemData[$key]["random_suffix"]
                    );
                }
            }
        }
        foreach ( $loots as $itemData )
        {
            $item = $itemData["item"];

            $loot = new Loot;
            $loot->encounter_id = $encounter->id;
            $loot->item_id = $item->id;
            $loot->count = $itemData["count"];
            $loot->random_prop = $itemData["random_prop"];
            $loot->random_suffix = $itemData["random_suffix"];

            $loot->save();
        }

        $encounter->top_processed = 1;
        $encounter->save();
    }
}