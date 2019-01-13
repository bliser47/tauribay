<?php

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;

class EncounterMember extends Model
{
    const CLASSES = array(

    );

    public static function getShortName($name)
    {
        return strlen($name) > 9 ? mb_substr($name,0,6) . ".." : $name;
    }
}