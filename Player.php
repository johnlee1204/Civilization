<?php
/**
 * Created by PhpStorm.
 * User: johnm
 * Date: 5/24/2018
 * Time: 9:29 PM
 */

namespace CivilizationPhp;


class Player
{
    private $name;
    private $nationName;
    private $money;
    private $units;
    private $nationPicture;
    private $cities;

    /**
     * Player constructor.
     * @param $name
     * @param $nationName
     * @param $nationPicture
     */

    function __construct($name, $nationName, $nationPicture)
    {
        GameRunner::$gameBoard;
        $this->name = $name;
        $this->nationName = $nationName;
        $this->cities = array();
        $this->units = array();
        $this->units[] = new Settler(GameRunner::getUnitId(),0,0,2,10,$nationName."\'s Settler",'',GameRunner::$gameBoard->findTile(rand(0,GameRunner::$gameBoard->getWidth()),rand(0,GameRunner::$gameBoard->getHeight())));
        $this->units[] = new Warrior(GameRunner::getUnitId(),30,20,2,100,$nationName."\'s Warrior",'',GameRunner::$gameBoard->findTile($this->units[0]->getPosTile()->getRow()+1,$this->units[0]->getPosTile()->getCol()+1));
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

    public function addUnit($newUnit){
        $this->units[] = $newUnit;
    }

}
