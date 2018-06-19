<?php
/**
 * Created by PhpStorm.
 * User: johnm
 * Date: 5/26/2018
 * Time: 4:57 PM
 */

namespace CivilizationPhp;
include_once('City.php');
include_once('Unit.php');
class Settler extends Unit
{
    public function __construct($id,$attack, $defense, $movementPoints, $health, $name, $image, Tile $posTile)
    {
        parent::__construct($id,$attack, $defense, $movementPoints, $health, $name, $image, $posTile);
    }

    public function foundCity($player,$tilePos,$cityName){
        if($this->canFoundCity($tilePos)){
            $player->cities[] = new City($cityName,1,5,array(),5,5,2,1,$tilePos);
        }
    }

    /**
     * @param Tile $tilePos
     * @return bool
     */
    function canFoundCity($tilePos){
        if(!$tilePos->getIsObstruct()){
            return true;
        }
        return false;
    }

}