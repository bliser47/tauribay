<?php

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;

class EncounterMember extends Model
{
    const ROLES = array(
        1 => array(
            "name" => "Tank",
            "classes" => array(
                1, 2, 6, 10, 11
            ),
            "specs" => array(
                73, 66, 250, 268, 104
            )
        ),
        2 => array(
            "name" => "DPS",
            "classes" => array(
                1,2,3,4,5,6,7,8,9,10,11
            ),
            "specs" => array(
                71,72, 70, 253, 254, 255, 259, 260, 261, 258, 251, 252, 262, 263, 62, 63, 64, 265, 266, 267, 269, 102, 103
            )
        ),
        3 => array(
            "name" => "Healer",
            "classes" => array(
                2, 5, 7, 10, 11
            ),
            "specs" => array(
                65, 256, 257, 264, 270, 105
            )
        )
    );

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

    public static function getRoles()
    {
        $roles = array();
        foreach ( self::ROLES as $roleId => $role )
        {
            $roles[$roleId] = $role["name"];
        }
        return $roles;
    }


    public static function getRoleClasses($_role_id)
    {
        $classIds = self::ROLES[$_role_id]["classes"];
        $classes = array();
        foreach ( $classIds as $id )
        {
            $classes[$id] = self::CLASSES[$id]["name"];
        }
        return $classes;
    }

    public static function getRoleSpecs($_role_id)
    {
        return self::ROLES[$_role_id]["specs"];
    }


    public static function getShortName($name)
    {
        return strlen($name) > 9 ? mb_substr($name,0,6) . ".." : $name;
    }

    public static function getRoleClassSpecs($_role_id, $_class_id)
    {
        $classSpecs = array();
        $class = self::CLASSES[$_class_id];
        foreach ( self::ROLES[$_role_id]["specs"] as $spec )
        {
            if ( array_key_exists($spec, $class["specs"]) )
            {
                $classSpecs[$spec] = $class["specs"][$spec];
            }
        }
        return $classSpecs;
    }
}