<?php


namespace TauriBay\Tauri;

class CreditIntent
{

    const CREDIT_INTENT_SELL = array
    (
        ' kredit adok ',
        ' kredit adok el ',
        ' kredit elado ',
        ' kredit eladok ',
        ' kreditet adok ',
        ' kreditet adok el',
        ' kreditet eladok ',
        ' kreditet eladok ',

        ' credit adok ',
        ' credit adok el ',
        ' credit elado ',
        ' credit eladok ',
        ' creditet adok ',
        ' creditet adok el',
        ' creditet eladok ',
        ' creditet eladok ',

        ' gold vennek ',
        ' gold venek ',
        ' gold vasarolnek ',
        ' gold veszek ',
        ' gold vasarolok ',

        ' goldot vennek ',
        ' goldot venek ',
        ' goldot vasarolnek ',
        ' goldot veszek ',
        ' goldot vasarolok ',

        ' aranyat vennek ',
        ' aranyat venek ',
        ' aranyat vasarolnek ',
        ' aranyat veszek ',
        ' aranyat vasarolok '
    );

    const CREDIT_INTENT_BUY = array
    (
        ' kreditet venni ',
        ' kreditet veszek ',
        ' kreditet vennek ',
        ' kreditet venek ',
        ' kreditet vasarolok ',
        ' kreditet vasarolnek ',

        ' creditet venni ',
        ' creditet veszek ',
        ' creditet vennek ',
        ' creditet venek ',
        ' creditet vasarolok ',
        ' creditet vasarolnek ',

        ' goldot adok ',
        ' goldot adok el ',
        ' gold elado '
    );


    const CREDIT_INTENTS = array(
        self::CREDIT_INTENT_SELL,
        self::CREDIT_INTENT_BUY,
    );

    public static function IsCreditTrade($_text) {

        foreach ( CreditIntent::CREDIT_INTENTS as $credit_intent_id => $credit_intent )
        {
            $pos = SmartParser::TextContainsArrayPart($_text,$credit_intent);
            if ( $pos !== false )
            {
                return $credit_intent_id;
            }
        }
        return false;
    }
}