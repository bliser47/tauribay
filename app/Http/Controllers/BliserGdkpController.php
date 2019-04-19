<?php

namespace TauriBay\Http\Controllers;

use Illuminate\Http\Request;
use TauriBay\AuthorizedCharacter;
use TauriBay\Characters;
use TauriBay\Defaults;
use TauriBay\Encounter;
use TauriBay\EncounterMember;
use TauriBay\Http\Requests;
use TauriBay\MemberTop;
use TauriBay\Realm;
use TauriBay\Tauri;
use Auth;
use TauriBay\Gdkp;
use Collective\Html\FormFacade;
use TauriBay\Tauri\CharacterClasses;
use TauriBay\Tauri\Skada;

class BliserGdkpController extends Controller
{
    public function character(Request $_request, $_character_id) {
        if ( $_character_id  !== null ) {
            $char = Characters::where("id","=",$_character_id)->first();
            if ( $char ) {
                $roles =  EncounterMember::getClassRoles($char->class);
                return FormFacade::select('role_id', $roles, 0, ['required', 'id' => 'application_role', 'class' => "control selectpicker input-large", 'placeholder' => __("Válassz role-t")]);
            } else {
                return "Char not found";
            }
        } else {
            return "Char id is null";
        }
    }

    public function apply(Request $_request)
    {
        $user = Auth::user();
        if ( $user && $_request->has("character_id") && $_request->has("role_id") ) {
            $userHasChar = AuthorizedCharacter::where("user_id","=",$user->id)->where("character_id","=",$_request->get("character_id"))->first();
            if ( $userHasChar ) {
                $applied = Gdkp::where("character_id","=",$_request->get("character_id"))->first();
                if ( !$applied ) {
                    $character = Characters::where("id",$_request->get("character_id"))->first();
                    $apply = new Gdkp;
                    $apply->account_id = $user->id;
                    $apply->character_id = $_request->get("character_id");
                    $totalScore = 0;
                    $ids = Encounter::getMapEncountersIds(Defaults::EXPANSION_ID, Defaults::MAP_ID);
                    $highestSpec = null;
                    $roleSpecs = array_keys(EncounterMember::getRoleClassSpecs($_request->get("role_id"), $character->class));
                    $highestSpecId = $roleSpecs[0];
                    foreach ( $ids as $id ) {
                        $bestScore = 0;
                        $tops = MemberTop::where("realm_id","=",$character->realm)->where("name","=",$character->name)
                            ->where("encounter_id","=",$id)->whereIn("difficulty_id",array(5,6))->whereIn("spec",$roleSpecs)->get();
                        foreach ( $tops as $top ) {
                            $thisScore = 0;
                            switch($_request->get("role_id")) {
                                case EncounterMember::ROLE_DPS:
                                case EncounterMember::ROLE_TANK:
                                    $thisScore = ($top->dps *100) / Encounter::getSpecTopDpsOnRealm($id, $top->difficulty_id, $top->spec, $character->realm);
                                break;

                                case EncounterMember::ROLE_HEAL:
                                    $thisScore = ($top->hps *100) / Encounter::getSpecTopDpsOnRealm($id, $top->difficulty_id, $top->spec, $character->realm);
                                    break;
                            }
                            if ( $thisScore > $bestScore ) {
                                $bestScore = $thisScore;
                            }
                            if ( $highestSpec == null || $bestScore > $highestSpec ) {
                                $highestSpec = $bestScore;
                                $highestSpecId = $top->spec;
                            }
                        }
                        $totalScore += $bestScore;
                    }
                    $apply->score = $totalScore;
                    $apply->spec = $highestSpecId;
                    $apply->save();
                }
            }
        }
        return redirect('/gdkp');
    }

    public function index(Request $_request)
    {
        $user = Auth::user();
        if ($user) {
            $applied = Gdkp::leftJoin("characters", "characters.id", "=", "gdkps.character_id")->orderBy("score", "desc")->get();
            $appliedIds = array();
            $appliedRoles = array(
                EncounterMember::ROLE_TANK => array(),
                EncounterMember::ROLE_HEAL => array(),
                EncounterMember::ROLE_DPS => array()
            );
            foreach ( $applied as $char ) {
                $appliedIds[] = $char->character_id;
                $char->percentageScore = Skada::calculatePercentage($char, $applied->first(), "score");
                $char->role = EncounterMember::getSpecRole($char->spec);
                $appliedRoles[$char->role][] = $char;
            }
            $charactersResult = AuthorizedCharacter::where("user_id", "=", $user->id)
                ->where("realm", "=", Realm::TAURI)->whereNotIn("character_id",$appliedIds)
                ->leftJoin("characters", "characters.id", "=", "authorized_characters.character_id")->get();
            $characters = array();
            foreach ($charactersResult as $char) {
                $characters[$char->id] = "[" . Realm::REALMS_SHORT[$char->realm] . "] " . $char->name;
            }
            $classSpecs = CharacterClasses::CLASS_SPEC_NAMES;
            $characterClasses = Tauri\CharacterClasses::CHARACTER_CLASS_NAMES;
            $roles = array();
            return view("gdkp", compact("characters", "appliedRoles", "characterClasses","roles","classSpecs"));
        }
        return redirect()->route('login', array("redirectTo"=>"/gdkp"));
    }
}
