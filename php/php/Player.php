<?php /** * Created by PhpStorm. * User: johnm * Date: 5/24/2018 * Time: 9:29 PM */

namespace CivilizationPhp;

use GameRunner;

include_once('Settler.php');
include_once('Archer.php');
include_once('Warrior.php');
include_once('GameRunner.php');
include_once('BoardOfTiles.php');
include_once('Tile.php');
include_once('Unit.php');

class Player
{
    public $name;
    public $nationName;
    public $money;
    public $units;
    public $nationPicture;
    public $cities;

    /**     * Player constructor.     * @param $name * @param $nationName     * @param $nationPicture */
    public function __construct($name, $nationName, $nationPicture)
    {
        GameRunner::$gameBoard;
        $this->name = $name;
        $this->nationName = $nationName;
        $this->cities = array();
        $this->units = array();
        $this->units[] = new Settler(GameRunner::getUnitId(), 0, 0, 2, 10, $nationName . "s Settler", 1, GameRunner::$gameBoard->findTileSafe(rand(0, GameRunner::$gameBoard->getHeight() - 2), rand(0, GameRunner::$gameBoard->getWidth() - 2)));
        $this->units[] = new Warrior(GameRunner::getUnitId(), 30, 20, 2, 100, $nationName . "s Warrior", 0, GameRunner::$gameBoard->findTile($this->units[0]->getPosTile()->getRow() + 1, $this->units[0]->getPosTile()->getCol() + 1));
        $this->nationPicture = $nationPicture;
        $this->money = 0;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getNationName()
    {
        return $this->nationName;
    }

    public function getMoney()
    {
        return $this->money;
    }

    public function getUnits()
    {
        return $this->units;
    }

    public function getNationPicture()
    {
        return $this->nationPicture;
    }

    public function setMoney($newMoneyValue)
    {
        $this->money = $newMoneyValue;
    }

    public function addUnit($newUnit)
    {
        $this->units[] = $newUnit;
    }
}