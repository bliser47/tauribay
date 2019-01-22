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
use TauriBay\EncounterMember;

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
                        // Ra-den 25 HC double entry
                        if ( $encounter["encounter_id"] == 1581 || $encounter["encounter_id"] == 1083 )
                        {
                            continue;
                        }
                        $encounters[$encounter["encounter_id"]] = $encounter["encounter_name"];
                    }
                    $encounters[0] = __("Minden boss");
                    return FormFacade::select('encounter_id', $encounters, 0, ['required', 'id' => 'encounter', 'class' => "control selectpicker input-large", 'placeholder' => __("Válassz bosst")]);
                }
            }
        }
        return "";
    }

    public function getClassSpecs(Request $_request, $_class_id)
    {
        $specs = EncounterMember::getSpecs($_class_id);
        $specs[0] = __("Minden spec");
        return FormFacade::select('spec_id', $specs, 0, ['required', 'id' => 'specs', 'class' => "control selectpicker input-large", 'placeholder' => __("Válassz spec-et")]);
    }

    public function getRoleClasses(Request $_request, $_role_id)
    {
        $classes = EncounterMember::getRoleClasses($_role_id);
        $classes[0] = __("Minden kaszt");
        return FormFacade::select('class_id', $classes, 0, ['required', 'id' => 'class', 'class' => "control selectpicker input-large", 'placeholder' => __("Válassz kasztot")]);
    }

    public function getRoleClassSpecs(Request $_request , $_role_id, $_class_id)
    {
        $specs = EncounterMember::getRoleClassSpecs($_role_id, $_class_id);
        $specs[0] = __("Minden spec");
        return FormFacade::select('spec_id', $specs, 0, ['required', 'id' => 'spec', 'class' => "control selectpicker input-large", 'placeholder' => __("Válassz role spec-et")]);

    }
}