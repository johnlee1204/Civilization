<?php
/**
 * Created by PhpStorm.
 * User: johnm
 * Date: 5/26/2018
 * Time: 4:39 PM
 */

namespace CivilizationPhp;
include_once('Unit.php');
include_once('Tile.php');

class Warrior extends Unit
{
    public function __construct($id,$attack, $defense, $movementPoints, $health, $name, $image, Tile $posTile)
    {
        parent::__construct($id,$attack, $defense, $movementPoints, $health, $name, $image, $posTile);
    }
}