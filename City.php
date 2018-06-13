<?php
/**
 * Created by PhpStorm.
 * User: johnm
 * Date: 5/24/2018
 * Time: 9:28 PM
 */

namespace CivilizationPhp;
include("Building.php");
class City
{
    private $name;
    private $population;
    private $size;
    private $buildings;
    private $defense;
    private $attack;
    private $production;
    private $science;

    function __construct($name, $population, $size, $buildings, $defense, $attack, $production, $science)
    {
        $this->name = $name;
        $this->population = $population;
        $this->size = $size;
        $this->buildings = $buildings;
        $this->defense = $defense;
        $this->attack = $attack;
        $this->production = $production;
        $this->science = $science;
    }

    public function addPopulation($population)
    {
        $this->population += $population;
    }

    public function addSize($size)
    {
        $this->size += $size;
    }

    public function addDefense($defense)
    {
        $this->defense += $defense;
    }

    public function addAttack($attack)
    {
        $this->attack += $attack;
    }

    public function addProduction($production)
    {
        $this->production += $production;
    }

    public function addScience($science)
    {
        $this->science += $science;
    }

    public function addBuildings($building)
    {
        $this->buildings[] = $building;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    private function checkForDuplicateBuilding($newBuilding)
    {
        foreach ($this->getBuildings() as $building) {
            if ($building->getType() == $newBuilding->getType()) {
                return false;
            }
        }
    }

    public function getBuildings()
    {
        return $this->buildings;
    }
}