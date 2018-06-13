<?php
/**
 * Created by PhpStorm.
 * User: johnm
 * Date: 5/26/2018
 * Time: 5:13 PM
 */

namespace CivilizationPhp;


class GameRunner
{
    private static $unitId;
    public static $gameBoard;
    static function start()
    {
        GameRunner::$gameBoard = new BoardOfTiles(50,50,32);;
    }
    static function getUnitId(){
        GameRunner::$unitId++;
        return GameRunner::$unitId-1;
    }

}