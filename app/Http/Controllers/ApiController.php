<?php

namespace TauriBay\Http\Controllers;

use TauriBay\CharacterTrade;
use TauriBay\GdkpTrade;
use TauriBay\CreditTrade;
use TauriBay\ParsedData;
use TauriBay\TradeData;
use TauriBay\Trader;
use TauriBay\Tauri\SmartParser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DB;
use TauriBay\Tauri\ApiClient;
use TauriBay\Http\Requests;
use DateTime;

class ApiController extends Controller
{
    public function ReceiveData(Request $_request)
    {
        $tradeDataJSON = json_decode($_request->getContent());
        $insertArray = array();
        for ($t = 0; $t < count($tradeDataJSON); $t++) {
            $json = $tradeDataJSON[$t];

            array_push($insertArray, array(
                "realm_id" => $json->realm_id,
                "data" => $json->text,
                "date" => Carbon::now()
            ));
        }
        TradeData::insert($insertArray);
        return $this->ParseData($tradeDataJSON);
    }



    public function ParseData($_data)
    {
        for ( $d = 0 ; $d < count($_data) ; $d++ )
        {
            $this->ParseSpecificData($_data[$d],false);
        }
    }

    public function ParseSpecificData($_data,$no_smart)
    {
        $realm_id = $_data->realm_id;
        $data = $_data->text;

        $textData1 = explode("[",$data);

        if ( count($textData1) > 1 ) {

            $textData2 = explode("]", $textData1[1], 2);

            if (count($textData1) > 0 && count($textData2) > 1) {

                $time = $textData1[0];
                $name = $textData2[0];
                $text = $textData2[1];

                $this->ParseSpecificTrade($time, $name, $text,$no_smart, $realm_id);
            }
        }
    }
    
    public function ParseSpecificTrade($_time,$_name,$_text,$no_smart, $_realm_id)
    {
        // 1. Do we know the person?
        $traderData = Trader::GetData($_name, $_realm_id);
        if ( $traderData )
        {
            $parsedData = ParsedData::where(array("realm_id" => $_realm_id, "name" => $_name,"text" => $_text))->first();
            if ( !$parsedData ) {
                $parsedData = new ParsedData;
                $parsedData->name = $_name;
                $parsedData->text = $_text;
                $parsedData->realm_id = $_realm_id;
            }

            $parsedData->faction = $traderData["faction"];
            $parsedData->class = $traderData["class"];
            $parsedData->updated_at = Carbon::now();
            $parsedData->save();
            if ( !$no_smart) {
                $this->SmartParseSpecific($parsedData->id, false);
            }
        }
    }

    public function SmartParseDebug(Request $_request, $_parsed_data_id)
    {
        $this->SmartParseSpecific($_parsed_data_id,true);
    }

    public function SmartParseRangeDebug(Request $_request, $_from, $_till)
    {
        ini_set('max_execution_time', 1000);
        for ( $s = $_from; $s < $_till ; $s++ )
        {
            $this->SmartParseSpecific($s,true);
        }
    }

    public function TradeParseRangeDebug(Request $_request, $_from, $_till)
    {
        ini_set('max_execution_time', 1000);
        for ( $s = $_from; $s < $_till ; $s++ )
        {
            $trade = TradeData::find($s);
            if ( $trade ) {
                $this->ParseSpecificData($trade->data, true);
            }
        }
    }

    public function SmartParseSpecific($_parsed_data_id,$no_update)
    {
        $parseData = ParsedData::find($_parsed_data_id);
        $smartResult = SmartParser::SmartParse($parseData->text);
        if (  array_key_exists("character_intent", $smartResult) && $smartResult['character_intent'] !== false &&
            array_key_exists("character_class", $smartResult) && $smartResult['character_class'] !== false )
        {
            $foundTrade = CharacterTrade::where(array("text"=>$parseData->text,"name"=>$parseData->name))->first();
            if ( !$foundTrade )
            {
                $mostSimmilarTrade = CharacterTrade::GetSimmilar($parseData->name,$parseData->text);
                if ( $mostSimmilarTrade === false ) {
                    $characterTrade = new CharacterTrade;
                }
                else {
                    $characterTrade = CharacterTrade::find($mostSimmilarTrade["id"]);
                }
            } else {
                $characterTrade = $foundTrade;
            }
            $characterTrade->name = $parseData->name;
            $characterTrade->text = $parseData->text;
            $characterTrade->faction = $parseData->faction ? $parseData->faction : 3;
            $characterTrade->intent = $smartResult['character_intent'];
            $characterTrade->class = $smartResult['character_class'];
            $characterTrade->realm_id = $parseData->realm_id;
            if ( !$no_update )
            {
                $characterTrade->updated_at = Carbon::now();
            }
            else {
                $characterTrade->updated_at = $parseData->updated_at;
            }
            $characterTrade->save();
        }
        else if ( array_key_exists("gdkp_intent", $smartResult) && $smartResult['gdkp_intent'] !== false )
        {
            if ( $smartResult['gdkp_data'] !== false )
            {
                $foundGdkp = GdkpTrade::where(array("text"=>$parseData->text,"name"=>$parseData->name))->first();
                if ( !$foundGdkp )
                {
                    $mostSimmilarGdkp = GdkpTrade::GetSimmilar($parseData->name,$parseData->text);
                    if ( $mostSimmilarGdkp === false ) {
                        $gdkpTrade = new GdkpTrade();
                    }
                    else {
                        $gdkpTrade = GdkpTrade::find($mostSimmilarGdkp["id"]);
                    }
                } else {
                    $gdkpTrade = $foundGdkp;
                }
                $gdkpTrade->realm_id = $parseData->realm_id;
                $gdkpTrade->name = $parseData->name;
                $gdkpTrade->text = $parseData->text;
                $gdkpTrade->faction = $parseData->faction ? $parseData->faction : 3;
                $gdkpTrade->intent = $smartResult['gdkp_intent'];
                $gdkpTrade->instance = $smartResult['gdkp_data']['wow_instance_id'] !== false ? $smartResult['gdkp_data']['wow_instance_id'] : 5;
                $gdkpTrade->size = $smartResult['gdkp_data']['wow_instance_size_id'] !== false ? $smartResult['gdkp_data']['wow_instance_size_id'] : 2;
                $gdkpTrade->difficulty = $smartResult['gdkp_data']['wow_instance_difficulty_id'] !== false ? $smartResult['gdkp_data']['wow_instance_difficulty_id'] : 2;

                if ( !$no_update )
                {
                    $gdkpTrade->updated_at = Carbon::now();
                }
                else {
                    $gdkpTrade->updated_at = $parseData->updated_at;
                }
                $gdkpTrade->save();
            }
            else {
                Log::warning('Coult not get data of gdkp ' . $_parsed_data_id . PHP_EOL);
            }
        }
        else if ( array_key_exists("credit_intent", $smartResult) && $smartResult['credit_intent'] !== false ) {

            $foundCredit = CreditTrade::where(array("text"=>$parseData->text,"name"=>$parseData->name))->first();
            if ( !$foundCredit )
            {
                $mostSimmilarCredit = CreditTrade::GetSimmilar($parseData->name,$parseData->text);
                if ( $mostSimmilarCredit === false ) {
                    $creditTrade = new CreditTrade();
                }
                else {
                    $creditTrade = CreditTrade::find($mostSimmilarCredit["id"]);
                }
            } else {
                $creditTrade = $foundCredit;
            }

            $creditTrade->realm_id = $parseData->realm_id;
            $creditTrade->name = $parseData->name;
            $creditTrade->text = $parseData->text;
            $creditTrade->faction = $parseData->faction ? $parseData->faction : 3;
            $creditTrade->intent = $smartResult['credit_intent'];


            if ( !$no_update )
            {
                $creditTrade->updated_at = Carbon::now();
            }
            else {
                $creditTrade->updated_at = $parseData->updated_at;
            }
            $creditTrade->save();
        }
    }


}
