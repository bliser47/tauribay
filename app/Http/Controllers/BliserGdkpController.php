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


class BliserGdkpController extends Controller
{
    public function apply(Request $_request)
    {
        $user = Auth::user();
        if ( $user && $_request->has("character_id") ) {
            $userHasChar = AuthorizedCharacter::where("user_id","=",$user->id)->where("character_id","=",$_request->get("character_id"))->first();
            if ( $userHasChar ) {
                $applied = Gdkp::where("character_id","=",$_request->get("character_id"))->first();
                if ( !$applied ) {
                    $character = Characters::where("character_id",$_request->get("character_id"))->first();
                    $apply = new Gdkp;
                    $apply->account_id = $user->id;
                    $apply->character_id = $_request->get("character_id");
                    $totalScore = 0;
                    $ids = Encounter::getMapEncountersIds(Defaults::EXPANSION_ID, Defaults::MAP_ID);
                    foreach ( $ids as $id ) {
                        $tops = MemberTop::where("realm_id","=",$character->realm)->where("name","=",$character->name)
                            ->where("encounter_id","=",$id)->whereIn("difficulty_id",array(4,6))->get();
                        foreach ( $tops as $top ) {
                            $dpsScore = Encounter::getSpecTopDps($id, $top->diffuclity_id, $top->spec) * $top->dps / 100;
                            $hpsScore = Encounter::getSpecTopHps($id, $top->diffuclity_id, $top->spec) * $top->hps / 100;
                        }
                    }
                    $score = $totalScore;
                    $apply->score = 0;
                    $apply->save();
                }
            }
        }
        return $this->index($_request);
    }

    public function index(Request $_request)
    {
        $user = Auth::user();
        if ($user) {
            $applied = Gdkp::leftJoin("characters", "characters.id", "=", "gdkps.character_id")->orderBy("score", "desc")->get();
            $appliedIds = array();
            foreach ( $applied as $char ) {
                $appliedIds[] = $char->character_id;
            }
            $charactersResult = AuthorizedCharacter::where("user_id", "=", $user->id)
                ->where("realm", "=", Realm::TAURI)->whereNotIn("character_id",$appliedIds)
                ->leftJoin("characters", "characters.id", "=", "authorized_characters.character_id")->get();
            $characters = array();
            foreach ($charactersResult as $char) {
                $characters[$char->id] = "[" . Realm::REALMS_SHORT[$char->realm] . "] " . $char->name;
            }
            $characterClasses = Tauri\CharacterClasses::CHARACTER_CLASS_NAMES;
            $roles = array();
            return view("gdkp", compact("characters", "applied", "characterClasses","roles"));
        }
        return redirect()->route('login');
    }
}
