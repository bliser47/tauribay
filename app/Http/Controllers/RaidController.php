<?php
/**
 * Created by PhpStorm.
 * User: thoma
 * Date: 1/11/2019
 * Time: 4:01 PM
 */

namespace TauriBay\Http\Controllers;

use Collective\Html\FormFacade;
use Illuminate\Http\Request;
use TauriBay\Encounter;

class RaidController extends Controller
{

    public function getExpansionMaps(Request $_request, $_expansion_id)
    {
        $expansionKey = "map_exp_".$_expansion_id;
        if ( array_key_exists($expansionKey, Encounter::EXPANSION_RAIDS_COMPLEX)) {
            $expansionMaps = Encounter::EXPANSION_RAIDS_COMPLEX[$expansionKey];
            $maps = array();
            foreach ( $expansionMaps as $map )
            {
                $maps[$map["id"]] = $map["name"];
            }
            return FormFacade::select('map_id', $maps, 0, ['required', 'id' => 'map', 'class' => "control selectpicker input-large", 'placeholder' => __("Válassz raidet")]);
        }
        return "";
    }

    public function getMapEncounters(Request $_request, $_expansion_id, $_map_id)
    {
        $expansionKey = "map_exp_".$_expansion_id;
        if ( array_key_exists($expansionKey, Encounter::EXPANSION_RAIDS_COMPLEX)) {
            $expansionMaps = Encounter::EXPANSION_RAIDS_COMPLEX[$expansionKey];
            foreach ($expansionMaps as $map) {
                if ( $map["id"] == $_map_id )
                {
                    $encounters = array();
                    foreach ( $map["encounters"] as $encounter )
                    {
                        $encounters[$encounter["encounter_id"]] = $encounter["encounter_name"];
                    }
                    return FormFacade::select('encounter_id', $encounters, 0, ['required', 'id' => 'encounter', 'class' => "control selectpicker input-large", 'placeholder' => __("Válassz bosst")]);
                }
            }
        }
        return "";
    }
}