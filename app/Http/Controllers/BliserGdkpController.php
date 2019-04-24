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

    public function apply(Request $_request, $_raid_id)
    {
        $user = Auth::user();
        if ( $user && $_request->has("character_id") && $_request->has("role_id") ) {
            $userHasChar = AuthorizedCharacter::where("user_id","=",$user->id)->where("character_id","=",$_request->get("character_id"))->first();
            if ( $userHasChar ) {
                $applied = Gdkp::where("gdkp_id","=",$_raid_id)->where("character_id","=",$_request->get("character_id"))->first();
                if ( !$applied ) {
                    $character = Characters::where("id",$_request->get("character_id"))->first();
                    $apply = new Gdkp;
                    $apply->gdkp_id = $_raid_id;
                    $apply->account_id = $user->id;
                    $apply->character_id = $_request->get("character_id");
                    $totalScore = 0;
                    $ids = Encounter::getMapEncountersIds(Defaults::EXPANSION_ID, Defaults::MAP_ID);
                    $highestSpec = null;
                    $roleSpecs = array_keys(EncounterMember::getRoleClassSpecs($_request->get("role_id"), $character->class));
                    $highestSpecId = $roleSpecs[0];
                    foreach ( $ids as $id ) {
                        $specBest = 0;
                        foreach ( $roleSpecs as $specId ) {
                            $bestSpec10Hc = PlayerController::getSpecTop($character->guid, $id, 5, $specId, true);
                            $bestSpec25Hc = PlayerController::getSpecTop($character->guid, $id, 6, $specId, true);
                            $best = max($bestSpec10Hc["score"],$bestSpec25Hc["score"]);
                            if ( $best > $specBest ) {
                                $specBest = $best;
                                if ( $highestSpec > $specBest ) {
                                    $highestSpec = $specBest;
                                    $highestSpecId = $specId;
                                }
                            }
                        }
                        $totalScore += $specBest;

                    }
                    $apply->score = $totalScore;
                    $apply->spec = $highestSpecId;
                    $apply->save();
                }
            }
        }
        return redirect('/raid/' . $_raid_id);
    }

    public function index(Request $_request, $_raid_id)
    {
        $user = Auth::user();
        if ($user) {
            $applied = Gdkp::where("gdkp_id","=",$_raid_id)->leftJoin("characters", "characters.id", "=", "gdkps.character_id")->orderBy("score", "desc")->get();
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
            $charactersResult = AuthorizedCharacter::where("user_id", "=", $user->id)->whereNotIn("character_id",$appliedIds)
                ->leftJoin("characters", "characters.id", "=", "authorized_characters.character_id")->get();
            $characterAppliedResults =  AuthorizedCharacter::where("user_id", "=", $user->id)->whereIn("character_id",$appliedIds)->get();
            $characters = array();
            foreach ($charactersResult as $char) {
                $characters[$char->id] = "[" . Realm::REALMS_SHORT[$char->realm] . "] " . $char->name;
            }
            $classSpecs = CharacterClasses::CLASS_SPEC_NAMES;
            $characterClasses = Tauri\CharacterClasses::CHARACTER_CLASS_NAMES;
            $roles = array();
            return view("gdkp", compact("characters", "appliedRoles", "characterClasses","roles","classSpecs","characterAppliedResults"));
        }
        return redirect()->route('login', array("redirectTo"=>"/raid/" . $_raid_id));
    }
}
