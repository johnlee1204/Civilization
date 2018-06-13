<?php
/**
 * Created by PhpStorm.
 * User: johnm
 * Date: 5/26/2018
 * Time: 4:39 PM
 */

namespace CivilizationPhp;


class Warrior extends Unit
{
    function __construct($id,$attack, $defense, $movementPoints, $health, $name, $image, Tile $posTile)
    {
        parent::__construct($id,$attack, $defense, $movementPoints, $health, $name, $image, $posTile);
    }
}