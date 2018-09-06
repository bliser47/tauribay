<?php

namespace TauriBay\Tauri;

class SmartParser
{

    const POSSIBLE_AFTER_CHARACTERS = array(':','(',')','/','-','!',',','.','_', ' ','>','<',1,2,3,4,5,6,7,8,9,0);

    public static function TextContainsArrayPart($_text,$array)
    {
        foreach($array as $value) {

            $pos = strpos($_text,$value);
            if ($pos !== false && ( strlen($value) > 3 || $pos < 3 ) ) {
                return $pos;
            }
            else
            {
                foreach ( self::POSSIBLE_AFTER_CHARACTERS as $character )
                {
                    $posAfter = strpos($_text, " " .trim($value) . $character);
                    if ($posAfter !== false && ( strlen($value) > 3 || $posAfter < 3 ) )
                        return $posAfter;
                    else {
                        $posBefore = strpos($_text,  $character . trim($value) . " ");
                        if ($posBefore !== false && ( strlen($value) > 3 || $posBefore < 3 ) ) {
                            return $posBefore;
                        }
                    }
                }
            }
        }
        return false;
    }

    public static function PrepareString($_text)
    {
        return " " . str_replace(explode(",","á,é,í,ö,ő,ó,ü,ű,ú"), explode(",","a,e,i,o,o,o,u,u,u"), mb_strtolower($_text)) . " ";
    }

    public static function SmartParse($_text)
    {
        $_text = self::PrepareString($_text);
        $smart_result = array();
        $smart_result["character_intent"] = CharacterIntent::IsCharacterTrade($_text);
        if ( $smart_result["character_intent"] !== false )
        {
            $smart_result["character_class"] = CharacterClasses::GetCharacterClass($_text);
        }
        else {
            $smart_result["gdkp_intent"] = GdkpIntent::IsGdkpTrade($_text);
            if ( $smart_result["gdkp_intent"] !== false )
            {
                $smart_result["gdkp_data"] = GdkpIntent::GetData($_text);
            }
        }
        return $smart_result;
    }
}