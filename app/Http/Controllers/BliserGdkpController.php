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
                    $apply = new Gdkp;
                    $apply->gdkp_id = $_raid_id;
                    $apply->account_id = $user->id;
                    $apply->character_id = $_request->get("character_id");
                    $apply->role_id = $_request->get("role_id");
                    $apply->save();
                }
            }
        }
        return redirect('/gdkp/' . $_raid_id);
    }

    public function index(Request $_request, $_raid_id)
    {
        $user = Auth::user();
        if ($user) {
            $applied = Gdkp::where("gdkp_id","=",$_raid_id)->
            leftJoin("characters", "characters.id", "=", "gdkps.character_id")->orderBy("characters.score", "desc")->get();
            $appliedIds = array();
            $appliedRoles = array(
                EncounterMember::ROLE_TANK => array(),
                EncounterMember::ROLE_HEAL => array(),
                EncounterMember::ROLE_DPS => array()
            );
            foreach ( $applied as $char ) {
                $appliedIds[] = $char->character_id;
                $char->percentageScore = Skada::calculatePercentage($char, $applied->where("role_id","=",$char->role_id)->first(), "score");
                $setSpec = array_keys(EncounterMember::getRoleClassSpecs($char->role_id, $char->class))[0];
                switch($setSpec) {
                    case 251:
                        $char->spec = 252;
                        break;
                    case 253:
                        $char->spec = 255;
                        break;
                    default:
                        $char->spec = $setSpec;
                        break;
                }
                $appliedRoles[$char->role_id][] = $char;
            }
            $charactersResult = AuthorizedCharacter::where("user_id", "=", $user->id)->whereNotIn("character_id",$appliedIds)
                ->leftJoin("characters", "characters.id", "=", "authorized_characters.character_id")
                ->orderBy("guid","desc")->get();
            $characterAppliedResults =  AuthorizedCharacter::where("user_id", "=", $user->id)->whereIn("character_id",$appliedIds)->get();
            $characters = array();
            $characterGuids = array();
            foreach ($charactersResult as $char) {
                if ( !in_array($char->guid, $characterGuids) ) {
                    $characters[$char->id] = "[" . Realm::REALMS_SHORT[0] . "] " . $char->name;
                    $characterGuids[] = $char->guid;
                }
            }
            $classSpecs = CharacterClasses::CLASS_SPEC_NAMES;
            $characterClasses = Tauri\CharacterClasses::CHARACTER_CLASS_NAMES;
            $roles = array();
            return view("gdkp", compact("characters", "appliedRoles", "characterClasses","roles","classSpecs","characterAppliedResults"));
        }
        return redirect()->route('login', array("redirectTo"=>"/gdkp/" . $_raid_id));
    }
}
