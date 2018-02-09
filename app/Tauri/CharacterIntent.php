<?php


namespace App\Tauri;

class CharacterIntent
{
    /*
     * 0 SELL/TRADE
     * 1 SELL
     * 2 BUY
     * 3 TRADE
     */

    const CHARACTER_INTENT_NAMES = array(
      'Eladó és Csere', 'Eladó', 'Vétel', 'Csere'
    );

    const CHARACTER_INTENT_SELL = array
    (
        ' e ',
        ' ea ',
        ' elad ',
        ' elado ',
        ' eladom ',
        ' csak elado ',
        ' csak eladom ',
        ' kizarolag elado ',
        ' kizarolag eladom ',
        ' eladni ',
        ' eladnam ',
        ' eladnek ',
        ' megvalok ',
        ' megvalnek '
    );

    const CHARACTER_INTENT_TRADE = array
    (
        ' cs ',
        ' csere ',
        ' cserel ',
        ' cserelni ',
        ' cserelem ',
        ' cserelnem ',
        ' cserelnek ',
    );

    const CHARACTER_INTENT_BUY = array
    (
        ' vesz ',
        ' venni ',
        ' vennek ',
        ' veszek ',
        ' vasarolok ',
        ' vasarolnek '
    );

    const CHARACTER_INTENT_SELL_OR_TRADE = array
    (
        ' e/cs ',
        ' e / cs ',
        ' e/ cs ',
        ' e /cs ',

        ' ea/cs ',
        ' ea / cs ',
        ' ea/ cs ',
        ' ea /cs ',

        ' ea/cser ',
        ' ea / cser ',
        ' ea/ cser ',
        ' ea /cser ',

        ' ea/csere ',
        ' ea / csere ',
        ' ea/ csere ',
        ' ea /csere ',


        ' elado/csere ',
        ' elado / csere ',
        ' elado/ csere ',
        ' elado /csere ',

        ' elado/cserelem ',
        ' elado / cserelem ',
        ' elado/ cserelem ',
        ' elado /cserelem ',

        ' elado/cserelnem ',
        ' elado / cserelnem ',
        ' elado/ cserelnem ',
        ' elado /cserelnem ',

        ' elado/cserelnek ',
        ' elado / cserelnek ',
        ' elado/ cserelnek ',
        ' elado /cserelnek ',


        ' eladom/csere ',
        ' eladom / csere ',
        ' eladom/ csere ',
        ' eladom /csere ',

        ' eladom/cserelem ',
        ' eladom / cserelem ',
        ' eladom/ cserelem ',
        ' eladom /cserelem ',

        ' eladom/cserelnem ',
        ' eladom / cserelnem ',
        ' eladom/ cserelnem ',
        ' eladom /cserelnem ',

        ' eladom/cserelnek ',
        ' eladom / cserelnek ',
        ' eladom/ cserelnek ',
        ' eladom /cserelnek ',


        ' ea vagy cs ',
        ' ea vagy cser ',
        ' ea vagy csere ',
        ' elado vagy cserelem ',
        ' elado vagy csere ',
        ' eladom vagy cserelem ',
        ' eladom vagy csere ',
        ' elado vagy cserelnem ',
        ' elado vagy cserelnek ',
        ' eladom vagy cserelnem ',
        ' eladom vagy cserelnek ',

        ' ea v cs ',
        ' ea v cser ',
        ' ea v csere ',
        ' elado v cserelem ',
        ' elado v csere ',
        ' eladom v cserelem ',
        ' eladom v csere ',
        ' elado v cserelnem ',
        ' elado v cserelnek ',
        ' eladom v cserelnem ',
        ' eladom v cserelnek ',

        ' ea cs',
        ' elado csere ',
        ' elado cserelem ',
        ' elado cserelnem ',
        ' elado cserelnek ',

        ' eladom csere ',
        ' eladom cserelem ',
        ' eladom cserelnem ',
        ' eladom cserelnek ',

        ' eladnam csere ',
        ' eladnam cserelem ',
        ' eladnam cserelnem ',
        ' eladnam cserelnek ',

        ' cs/e ',
        ' cs / e ',
        ' cs/ e ',
        ' cs /e ',

        ' cs/ea ',
        ' cs / ea ',
        ' cs/ ea ',
        ' cs /ea ',

        ' cser/ea ',
        ' cser / ea ',
        ' cser/ ea ',
        ' cser /ea ',

        ' csere/ea ',
        ' csere / ea ',
        ' csere/ ea ',
        ' csere /ea ',

        ' csere/elado ',
        ' csere / elado ',
        ' csere/ elado ',
        ' csere /elado ',

        ' csere/eladom ',
        ' csere / eladom ',
        ' csere/ eladom ',
        ' csere /eladom ',


        ' cserelem/elado ',
        ' cserelem / elado ',
        ' cserelem/ elado ',
        ' cserelem /elado ',

        ' cserelem/eladom ',
        ' cserelem / eladom ',
        ' cserelem/ eladom ',
        ' cserelem /eladom ',

        ' cserelnem/elado ',
        ' cserelnem / elado ',
        ' cserelnem/ elado ',
        ' cserelnem /elado ',

        ' cserelnek/eladom ',
        ' cserelnek / eladom ',
        ' cserelnek/ eladom ',
        ' cserelnek /eladom ',



        ' cs vagy ea ',
        ' cser vagy ea ',
        ' csere vagy ea ',
        ' cserelem vagy elado ',
        ' csere vagy elado ',
        ' cserelem vagy eladom ',
        ' csere vagy eladom ',
        ' cserelnem vagy elado ',
        ' cserelnek vagy eladom ',


        ' cs v ea ',
        ' cser v ea ',
        ' csere v ea ',
        ' cserelem v elado ',
        ' csere v elado ',
        ' cserelem v eladom ',
        ' csere v eladom ',
        ' cserelnem v elado ',
        ' cserelnek v eladom ',

        ' cs ea',
        ' csere elado ',
        ' cserelem elado ',
        ' cserelnem elado ',
        ' cserelnek elado ',

        ' csere eladom ',
        ' cserelem eladom ',
        ' cserelnem eladom ',
        ' cserelnek eladom ',

        ' csere eladnam ',
        ' cserelem eladnam ',
        ' cserelnem eladnam ',
        ' cserelnek eladnam ',
    );

    const CHARACTER_INTENTS = array(
        self::CHARACTER_INTENT_SELL_OR_TRADE,
        self::CHARACTER_INTENT_SELL,
        self::CHARACTER_INTENT_BUY,
        self::CHARACTER_INTENT_TRADE
    );

    public static function IsCharacterTrade($_text)
    {
        $foundIntents = array();
        foreach ( CharacterIntent::CHARACTER_INTENTS as $character_intent_id => $character_intent )
        {
            $pos = SmartParser::TextContainsArrayPart($_text,$character_intent);
            if ( $pos !== false )
            {
                if ( $character_intent_id < 1 )
                {
                    return $character_intent_id;
                }
                array_push($foundIntents,array(
                    "pos" => $pos,
                    "character_intent_id" => $character_intent_id
                ));
            }
        }
        if ( count($foundIntents) > 0 ) {
            usort($foundIntents, function ($a, $b) {
                return $a["pos"] > $b["pos"];
            });
            return $foundIntents[0]["character_intent_id"];
        }
        return false;
    }

}