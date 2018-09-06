<?php

namespace  TauriBay\Tauri;

class CharacterClasses
{
    /*
     * 0 Uknown
     * 1 Warrior
     * 2 Paladin
     * 3 Hunter
     * 4 Rogue
     * 5 Priest
     * 6 Death Knight
     * 7 Shaman
     * 8 Mage
     * 9 Warlock
     * 10 Druid
     * 11 Monk
     */

    const CHARACTER_CLASS_NAMES = array(
        'Karakter', 'Warrior', 'Paladin', 'Hunter', 'Rogue', 'Priest', 'Death Knight', 'Shaman', 'Mage', 'Warlock', 'Druid', 'Monk'
    );

    const CHARACTER_CLASS_WARRIOR = array
    (
        ' wari ',
        ' warit ',
        ' warim ',
        ' warimat ',

        ' warrior ',
        ' warriort ',
        ' warriorom ',
        ' warrioromat ',

        ' warior ',
        ' wariort ',
        ' wariorom ',
        ' warioromat ',

        ' warri ',
        ' warrit ',
        ' warrim ',
        ' warrimat '
    );

    const CHARACTER_CLASS_PALADIN = array
    (
        ' paladin ',
        ' paladint ',
        ' paladinom ',
        ' paladinomat ',

        ' pala ',
        ' palat ',
        ' palam ',
        ' palamat ',

        ' pally ',
        ' pallyt ',
        ' pallym ',
        ' pallymat ',

        ' paly ',
        ' palyt ',
        ' palym ',
        ' palymat '

    );

    const CHARACTER_CLASS_HUNTER = array
    (
        ' hunter ',
        ' huntert ',
        ' hunterem ',
        ' hunteremet ',

        ' hunti ',
        ' huntit ',
        ' huntim ',
        ' huntimat '
    );

    const CHARACTER_CLASS_ROGUE = array
    (
        ' rogue ',
        ' roguet ',
        ' rogueom ',
        ' rogueomat ',

        ' rugo ',
        ' rugot ',
        ' rugom ',
        ' rugomat ',

        ' zsivany ',
        ' zsivanyt ',
        ' zsivanyom ',
        ' zsivanyomat ',

        ' rog ',
        ' rogot ',
        ' rogom ',
        ' rogomat '

    );

    const CHARACTER_CLASS_PRIEST = array
    (
        ' priest ',
        ' priestet ',
        ' priestem ',
        ' priestemet ',

        ' pap ',
        ' papot ',
        ' papom ',
        ' papomat '
    );

    const CHARACTER_CLASS_DEATH_KNIGHT = array
    (
        ' dk ',
        ' dkt ',
        ' dk-t ',
        ' dkm ',
        ' dk-am',
        ' dk-m ',
        ' dk-mat',
        ' dkmat ',
        ' dk-mat ',

        ' deathknight ',
        ' deathknightot ',
        ' deathknightom ',
        ' deathknightomat ',

        ' death knight ',
        ' death knightot ',
        ' death knightom ',
        ' death knightomat '
    );

    const CHARACTER_CLASS_SHAMAN = array
    (
        ' sami ',
        ' samit ',
        ' samim ',
        ' samimat ',

        ' shami ',
        ' shamit ',
        ' shamim ',
        ' shamimat ',

        ' saman ',
        ' samant ',
        ' samanom ',
        ' samanomat ',

        ' shaman ',
        ' shamant ',
        ' shamanom ',
        ' shamanomat ',

        ' samli ',
        ' samlit ',
        ' samlim ',
        ' samlimat ',

        ' shamli ',
        ' shamlit ',
        ' shamlim',
        ' shamilmat '
    );

    const CHARACTER_CLASS_MAGE = array
    (
        ' mage ',
        ' maget ',
        ' magem ',
        ' magemet ',

        ' magus ',
        ' magusom ',
        ' magust ',
        ' magusomat '
    );

    const CHARACTER_CLASS_WARLOCK = array
    (
        ' lock ',
        ' lockot ',
        ' lockom ',
        ' lockomat ',
    
        ' warlock ',
        ' warlockot ',
        ' warlockom ',
        ' warlockomat '
    );

    const CHARACTER_CLASS_DRUID = array
    (
        ' druid ',
        ' druidot ',
        ' druidom ',
        ' druidomat ',

        ' dudu ',
        ' dudut ',
        ' dudum ',
        ' dudumat ',

        ' druida ',
        ' druidat ',
        ' druidam ',
        ' druidamat '
    );

    const CHARACTER_CLASS_MONK = array
    (
        ' monk ',
        ' monkot ',
        ' monkom ',
        ' monkomat '
    );

    const CHARACTER_CLASS_UNKNOWN = array
    (
        ' kari ',
        ' karit ',
        ' karaktert ',
    );

    const CHARACTER_CLASSES = array(
        self::CHARACTER_CLASS_UNKNOWN,
        self::CHARACTER_CLASS_WARRIOR,
        self::CHARACTER_CLASS_PALADIN,
        self::CHARACTER_CLASS_HUNTER,
        self::CHARACTER_CLASS_ROGUE,
        self::CHARACTER_CLASS_PRIEST,
        self::CHARACTER_CLASS_DEATH_KNIGHT,
        self::CHARACTER_CLASS_SHAMAN,
        self::CHARACTER_CLASS_MAGE,
        self::CHARACTER_CLASS_WARLOCK,
        self::CHARACTER_CLASS_DRUID,
        self::CHARACTER_CLASS_MONK
    );

    public static function GetCharacterClass($_text)
    {
        $foundClasses = array();
        foreach ( CharacterClasses::CHARACTER_CLASSES as $character_class_id => $character_class )
        {
            $pos = SmartParser::TextContainsArrayPart($_text,$character_class);
            if ( $pos !== false )
            {
                array_push($foundClasses,array(
                    "pos" => $pos,
                    "character_class_id" => $character_class_id
                ));
            }
        }
        if ( count($foundClasses) > 0 ) {
            usort($foundClasses, function ($a, $b) {
                return $a["pos"] > $b["pos"];
            });
            return $foundClasses[0]["character_class_id"];
        }
        return false;
    }

    public static function ConvertRaceToFaction($_race)
    {
        if ( in_array($_race,array(2,5,6,8,9,10,26)) )
        {
            return 1;
        }
        return 2;
    }

}