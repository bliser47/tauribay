<?php

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;

class EncounterMember extends Model
{
    const SPEC_DRUID_BALALANCE = 102;
    const SPEC_DRUID_GUARDIAN = 104;

    const ROLE_TANK = 1;
    const ROLE_DPS = 2;
    const ROLE_HEAL = 3;

    const WARRIOR = 1;
    const PALADIN = 2;
    const HUNTER = 3;
    const ROGUE = 4;
    const PRIEST = 5;
    const DEATH_KNIGHT = 6;
    const SHAMAN = 7;
    const MAGE = 8;
    const WARLOCK = 9;
    const DRUID = 10;
    const MONK = 11;

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
                71,72, 70, 253, 254, 255, 259, 260, 261, 258, 251, 252, 262, 263, 62, 63, 64, 265, 266, 267, 269, self::SPEC_DRUID_BALALANCE, 103
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

    const ROLES_SHORT = array(
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
                71,72, 70, 253, 254, 255, 259, 260, 261, 258, 251, 252, 262, 263, 62, 63, 64, 265, 266, 267, 269, self::SPEC_DRUID_BALALANCE, 103
            )
        ),
        3 => array(
            "name" => "Heal",
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
                self::SPEC_DRUID_BALALANCE => "Balance",
                103 => "Feral",
                104 => "Guardian",
                105 => "Restoration",
            )
        )
    );

    const CLASSES_SHORT = array(
        1 => array(
            "name" => "Warrior",
            "specs" => array(
                71 => "Arms",
                72 => "Fury",
                73 => "Prot"
            )
        ),
        2 => array(
            "name" => "Paladin",
            "specs" => array(
                65 => "Holy",
                66 => "Prot",
                70 => "Ret"
            )
        ),
        3 => array(
            "name" => "Hunter",
            "specs" => array(
                253 => "BM",
                254 => "MM",
                255 => "Survi"
            )
        ),
        4 => array(
            "name" => "Rogue",
            "specs" => array(
                259 => "Assa",
                260 => "Combat",
                261 => "Sub"
            )
        ),
        5 => array(
            "name" => "Priest",
            "specs" => array(
                256 => "Disc",
                257 => "Holy",
                258 => "SH"
            )
        ),
        6 => array(
            "name" => "DK",
            "specs" => array(
                250 => "Blood",
                251 => "Frost",
                252 => "UH"
            )
        ),
        7 => array(
            "name" => "Shaman",
            "specs" => array(
                262 => "Elem",
                263 => "Enha",
                264 => "Resto"
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
                265 => "Affli",
                266 => "Demo",
                267 => "Destru",
            )
        ),
        10 => array(
            "name" => "Monk",
            "specs" => array(
                268 => "BM",
                269 => "WW",
                270 => "MW"
            )
        ),
        11 => array(
            "name" => "Druid",
            "specs" => array(
                self::SPEC_DRUID_BALALANCE => "Balance",
                103 => "Feral",
                104 => "Guard",
                105 => "Resto",
            )
        )
    );

    const CLASSES_CAN_HEAL = array(
        2,5,7,10,11
    );

    const CLASSES_CAN_TANK = array(
        1,2,6,10,11
    );

    const SPEC_IS_HEAL = array(
        65, 256, 257, 264, 270, 105
    );

    const SPEC_IS_TANK = array(
        73, 66, 250, 268, 104
    );

    public static function canClassHeal($class) {
        return in_array($class,self::CLASSES_CAN_HEAL);
    }

    public static function canClassTank($class) {
        return in_array($class,self::CLASSES_CAN_TANK);
    }

    public static function isHealer($spec) {
        return in_array($spec,self::SPEC_IS_HEAL);
    }

    public static function isTank($spec) {
        return in_array($spec,self::SPEC_IS_TANK);
    }

    public static function getClasses()
    {
        $classes = array();
        foreach ( self::CLASSES as $classId => $class )
        {
            $classes[$classId] = $class["name"];
        }
        return $classes;
    }

    public static function getClassesShort()
    {
        $classes = array();
        foreach ( self::CLASSES_SHORT as $classId => $class )
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

    public static function getSpecsShort($_class_id)
    {
        $specs = array();
        foreach ( self::CLASSES_SHORT[$_class_id]["specs"] as $specId => $specName )
        {
            $specs[$specId] = $specName;
        }
        return $specs;
    }


    public static function getSpecRole($_spec_id)
    {
        foreach ( self::ROLES as $roleId => $role )
        {
            if ( in_array($_spec_id, $role["specs"]) ) {
                return $roleId;
            }
        }
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

    public static function getRolesShort()
    {
        $roles = array();
        foreach ( self::ROLES_SHORT as $roleId => $role )
        {
            $roles[$roleId] = $role["name"];
        }
        return $roles;
    }



    public static function getRoleClasses($_role_id)
    {
        if ( $_role_id == 0 )
        {
            return self::getClasses();
        }
        $classIds = self::ROLES[$_role_id]["classes"];
        $classes = array();
        foreach ( $classIds as $id )
        {
            $classes[$id] = self::CLASSES[$id]["name"];
        }
        return $classes;
    }

    public static function getRoleClassesShort($_role_id)
    {
        if ( $_role_id == 0 )
        {
            return self::getClasses();
        }
        $classIds = self::ROLES[$_role_id]["classes"];
        $classes = array();
        foreach ( $classIds as $id )
        {
            $classes[$id] = self::CLASSES_SHORT[$id]["name"];
        }
        return $classes;
    }

    public static function getRoleSpecs($_role_id)
    {
        if ( $_role_id == 0 )
        {
            return array_merge(
                self::ROLES[1]["specs"],
                self::ROLES[2]["specs"],
                self::ROLES[3]["specs"]
            );
        }
        return self::ROLES[$_role_id]["specs"];
    }

    public static function getRoleSpecsShort($_role_id)
    {
        if ( $_role_id == 0 )
        {
            return array_merge(
                self::ROLES_SHORT[1]["specs"],
                self::ROLES_SHORT[2]["specs"],
                self::ROLES_SHORT[3]["specs"]
            );
        }
        return self::ROLES_SHORT[$_role_id]["specs"];
    }


    public static function getShortName($name)
    {
        return strlen($name) > 9 ? mb_substr($name,0,6) . ".." : $name;
    }

    public static function getRoleClassSpecs($_role_id, $_class_id)
    {
        $classSpecs = array();
        $class = self::CLASSES[$_class_id];
        if ( $_role_id != 0 ) {
            foreach (self::ROLES[$_role_id]["specs"] as $spec) {
                if (array_key_exists($spec, $class["specs"])) {
                    $classSpecs[$spec] = $class["specs"][$spec];
                }
            }
        }
        else
        {
            $classSpecs = $class["specs"];
        }
        return $classSpecs;
    }

    public static function getRoleClassSpecsShort($_role_id, $_class_id)
    {
        $classSpecs = array();
        $class = self::CLASSES_SHORT[$_class_id];
        if ( $_role_id != 0 ) {
            foreach (self::ROLES_SHORT[$_role_id]["specs"] as $spec) {
                if (array_key_exists($spec, $class["specs"])) {
                    $classSpecs[$spec] = $class["specs"][$spec];
                }
            }
        }
        else
        {
            $classSpecs = $class["specs"];
        }
        return $classSpecs;
    }

    public static function getSpecClass($_spec_id) {
        foreach ( self::CLASSES as $classId => $class )
        {
            if ( array_key_exists($_spec_id,$class["specs"]) ) {
                return $classId;
            }
        }
        return 0;
    }

    public static function getClassRoles($_class_id) {
        $roles = array();
        foreach ( self::ROLES as $roleId => $role ) {
            if ( in_array($_class_id, $role["classes"]) ) {
                $roles[$roleId] = $role["name"];
            }
        }
        return $roles;
    }

    public static function findFaction($member) {

    }
}