<?php

namespace TauriBay\Http\Controllers;

use Illuminate\Http\Request;
use TauriBay\AuthorizedCharacter;
use TauriBay\Characters;
use TauriBay\Defaults;
use TauriBay\Encounter;
use TauriBay\EncounterMember;
use TauriBay\Http\Requests;
use TauriBay\Loot;
use TauriBay\MemberTop;
use TauriBay\Realm;
use TauriBay\Tauri;
use Auth;
use TauriBay\Gdkp;
use Collective\Html\FormFacade;
use TauriBay\Tauri\CharacterClasses;
use TauriBay\Tauri\Skada;
use TauriBay\Item;

class BMAHController extends Controller
{
    public function debug() {
        $api = new Tauri\ApiClient();
        $realms = array();
        foreach ( Realm::getAllRealmIds() as $realmId ) {
            $realm = array();
            $realm["items"] = array();
            $realm["active"] = $realmId == "tauri";
            $data = $api->getBMAuctionsData(Realm::REALMS[$realmId]);
            return $data;
        }
    }

    private function getTimeLeft($key) {
        switch($key) {
            case "SHORT":
                return "<span style=\"color:red\"><b><30m</b></span>";
                break;
            case "MEDIUM":
                return "<span style=\"color:red\"><2h</span>";
                break;
            case "LONG":
                return "<span style=\"color:orange\">>2h</span>";
                break;
            case "VERY_LONG":
                return "<span style=\"color:green\">>12h</span>";
                break;
        }
        return "?";
    }

    private function getTimeLeftNumber($key) {
        switch($key) {
            case "SHORT":
                return 0;
                break;
            case "MEDIUM":
                return 1;
                break;
            case "LONG":
                return 2;
                break;
            case "VERY_LONG":
                return 3;
                break;
        }
        return 4;
    }

    public function index() {
        $api = new Tauri\ApiClient();
        $realms = array();
        foreach ( Realm::getAllRealmIds() as $realmId ) {
            $realm = array();
            $realm["items"] = array();
            $realm["active"] = $realmId == "tauri";
            $data = $api->getBMAuctionsData(Realm::REALMS[$realmId]);
            foreach ( $data["response"]["auctions"] as $auction ) {
                $item = Item::where("item_id","=",$auction["item"])->first();
                if ( !$item ) {
                    $itemData = $api->getItemTooltipDataByEntry(Realm::REALMS[$realmId], $auction["item"]);
                    $itemObject = $itemData["response"];
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
                }
                if($item->icon == "leather_pvpdruid_f_01boot copy.jpg") {
                    $item->icon = "leather_pvpdruid_f_01boot-copy.png";
                }
                $item->timeLeft = $this->getTimeLeft($auction["timeLeft"]);
                $item->timeLeftNr = $this->getTimeLeftNumber($auction["timeLeft"]);
                $realm["items"][] = $item;
            }
            usort($realm["items"],function($item1,$item2) {
                return $item1->timeLeftNr > $item2->timeLeftNr;
            });
            $realms[Realm::REALMS_SHORT[$realmId]] = $realm;
        }
        return view("bmah/bmah")->with(compact("realms"));
    }

}