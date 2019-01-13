<?php

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;

class EncounterMember extends Model
{
    const CLASSES = array(
        1 => array(
            "name" => "Warrior",
            "specs" => array(
                71 => "Arms",
                72 => "Fury",
                73 => "Protection"
            )
        ),
        2 => array(
            "name" => "Paladin",
            "specs" => array(
                65 => "Holy",
                66 => "Protection",
                70 => "Retribution"
            )
        ),
        3 => array(
            "name" => "Hunter",
            "specs" => array(
                253 => "Beast Mastery",
                254 => "Marksmanship",
                255 => "Survival"
            )
        ),
        4 => array(
            "name" => "Rogue",
            "specs" => array(
                259 => "Assassination",
                260 => "Combat",
                261 => "Subtlety"
            )
        ),
        5 => array(
            "name" => "Priest",
            "specs" => array(
                256 => "Discipline",
                257 => "Holy",
                258 => "Shadow"
            )
        ),
        6 => array(
            "name" => "Death Knight",
            "specs" => array(
                250 => "Blood",
                251 => "Frost",
                252 => "Unholy"
            )
        ),
        7 => array(
            "name" => "Shaman",
            "specs" => array(
                262 => "Elemental",
                263 => "Enhancement",
                264 => "Restoration"
            )
        ),
        8 => array(
            "name" => "Mage",
            "specs" => array(
                62 => "Arcane",
                63 => "Fire",
                64 => "Frost"
            )
        ),
        9 => array(
            "name" => "Warlock",
            "specs" => array(
                265 => "Affliction",
                266 => "Demonology",
                267 => "Destruction",
            )
        ),
        10 => array(
            "name" => "Monk",
            "specs" => array(
                268 => "Brewmaster",
                269 => "Windwalker",
                270 => "Mistweaver"
            )
        ),
        11 => array(
            "name" => "Druid",
            "specs" => array(
                102 => "Balance",
                103 => "Feral",
                104 => "Guardian",
                105 => "Restoration",
            )
        )
    );


    public static function getClasses()
    {
        $classes = array();
        foreach ( self::CLASSES as $classId => $class )
        {
            $classes[$classId] = $class["name"];
        }
        return $classes;
    }

    public static function getSpecs($_class_id)
    {
        $specs = array();
        foreach ( self::CLASSES[$_class_id]["specs"] as $specId => $specName )
        {
            $specs[$specId] = $specName;
        }
        return $specs;
    }

    public static function getShortName($name)
    {
        return strlen($name) > 9 ? mb_substr($name,0,6) . ".." : $name;
    }
}