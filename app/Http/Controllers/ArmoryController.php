<?php

namespace TauriBay\Http\Controllers;

use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use TauriBay\Http\Requests;
use TauriBay\Tauri;
use TauriBay\Realm;

class ArmoryController extends Controller
{
    const REALMS = array(
        0 => "[HU] Tauri WoW Server",
        1 => "[HU] Warriors of Darkness",
        2 => "[EN] Evermoon"
    );

    public function Request(Request $_request)
    {
         $validator = Validator::make($_request->all(), [
             'realm' => [
                'required',
                Rule::in(0,1,2)
             ],
             'characterName' => 'required'
         ]);

         if ( !$validator->fails() )
         {
             $api = new Tauri\ApiClient();
             $characterSheet = $api->getCharacterSheet(Realm::REALMS[$_request->get('realm')], $_request->get('characterName'));

             return view('home.adverts.character-gear', array("armoryData" => $characterSheet));
         }
         else
         {
            return $validator->errors();
         }
    }
}
