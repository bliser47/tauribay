<?php
/**
 * Created by PhpStorm.
 * User: Tamas
 * Date: 1/8/2019
 * Time: 5:14 PM
 */

namespace TauriBay\Http\Controllers;

use Illuminate\Http\Request;
use TauriBay\EncounterMember;
use TauriBay\Realm;
use TauriBay\Encounter;
use TauriBay\Tauri\Skada;
use TauriBay\Tauri\CharacterClasses;
use Carbon\Carbon;


class EncounterController extends Controller
{
    public function index(Request $_request, $encounter_name_url)
    {

    }

    public function log(Request $_request, $encounter_id, $logid)
    {
        $encounter = Encounter::where("encounters.id", "=", $logid)
            ->leftJoin('guilds', 'encounters.guild_id', '=', 'guilds.id')
            ->select(array
                (
                    "encounters.id as id",
                    "encounters.encounter_id as encounter_id",
                    "encounters.realm_id as realm_id",
                    "guilds.name as name",
                    "encounters.killtime as killtime",
                    "encounters.fight_time as fight_time",
                    "guilds.faction as faction",
                    "guilds.id as guild_id",
                    "encounters.wipes as wipes",
                    "encounters.deaths_total as deaths_total",
                    "encounters.deaths_fight as deaths_fight",
                )
            )
            ->first();

        $members = EncounterMember::where("encounter_id", "=", $encounter->id)->get();

        $membersDamage = $members->sortByDesc("damage_done");



        foreach ( $members as $member )
        {
            $member->total_heal = $member->heal_done + $member->damage_absorb;
        }

        $membersHealing = $members->sortByDesc("total_heal");

        foreach ( $membersDamage as $member ) {
            $member->dps = Skada::calculatePS($encounter, $member, "damage_done");
            $member->dpsNonFormatted = Skada::calculatePS($encounter, $member, "damage_done", true);
            $member->percentageDamage = Skada::calculatePercentage($member, $membersDamage->first(), "damage_done");
        }

        foreach ( $membersHealing as $member ) {
            $member->hps = Skada::calculatePS($encounter, $member, "total_heal");
            $member->hpsNonFormatted = Skada::calculatePS($encounter, $member, "total_heal", true);
            $member->percentageHealing = Skada::calculatePercentage($member, $membersHealing->first(), "total_heal");
        }

        $encounterData = Encounter::ENCOUNTER_IDS[$encounter->encounter_id];
        $realms = Realm::REALMS;
        $shortRealms = Realm::REALMS_SHORT;
        $characterClasses = CharacterClasses::CHARACTER_CLASS_NAMES;
        $classSpecs = CharacterClasses::CLASS_SPEC_NAMES;

        return view("encounter/encounter" , compact("encounter","encounterData", "membersDamage", "membersDps", "membersHealing", "membersHps","totalDmg","characterClasses","classSpecs","realms","shortRealms"));
    }

    public function fixMissing1()
    {
        $missing = EncounterMember::where("encounter", "=" ,"")->limit(1000)->get();
        $added = 0;
        foreach ( $missing as $encounterMember )
        {
            $enc = Encounter::where("id","=",$encounterMember->encounter_id)->first();
            $encounterMember->encounter = $enc->encounter_id;
            $encounterMember->difficulty_id = $enc->difficulty_id;
            $encounterMember->fight_time = $enc->fight_time;
            $encounterMember->dps = $encounterMember->damage_done / ( $enc->fight_time / 1000 );
            $encounterMember->hps = ($encounterMember->heal_done + $encounterMember->absorb_done - $encounterMember->overheal) / ( $enc->fight_time / 1000 );
            $encounterMember->save();

            $added++;
        }
        $this->fixMissing1();
    }

    public function fixMissing(Request $_request)
    {
        ini_set('max_execution_time', 0);
        $this->fixMissing1();
    }
}