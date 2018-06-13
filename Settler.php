<?php
/**
 * Created by PhpStorm.
 * User: johnm
 * Date: 5/26/2018
 * Time: 4:57 PM
 */

namespace CivilizationPhp;


class Settler extends Unit
{
    function __construct($id,$attack, $defense, $movementPoints, $health, $name, $image, Tile $posTile)
    {
        parent::__construct($id,$attack, $defense, $movementPoints, $health, $name, $image, $posTile);
    }

    public function foundCity($player,$tilePos,$cityName){
        if($this->canFoundCity($tilePos)){
            $player->cities[] = new City($cityName,1,5,array(),5,5,2,1);
        }
    }

    /**
     * @param Tile $tilePos
     * @return bool
     */
    function canFoundCity($tilePos){
        if(!GameRunner::$gameBoard->findTile($tilePos->getRow(),$tilePos->getCol())->getIsObstruct()){
            return true;
        }
        return false;
    }

}