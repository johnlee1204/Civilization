<<<<<<< HEAD
<?php /** * Created by PhpStorm. * User: johnm * Date: 5/24/2018 * Time: 9:28 PM */

namespace CivilizationPhp;
use GameRunner;

=======
<?php
/**
 * Created by PhpStorm.
 * User: johnm
 * Date: 5/24/2018
 * Time: 9:28 PM
 */

namespace CivilizationPhp;
>>>>>>> 202ef54839033042ef5ca7abbb7fe11b4d9c5583
include_once("Building.php");
include_once('GameRunner.php');
include_once('BoardOfTiles.php');
include_once('Tile.php');
<<<<<<< HEAD

class City
{
    public $name;
    public $population;
    public $size;
    public $buildings;
    public $defense;
    public $attack;
    public $production;
    public $science;
    public $tiles;
    public $playerId;

    public function __construct($playerId,$name, $population, $size, $buildings, $defense, $attack, $production, $science, $tilePos)
    {
        GameRunner::$gameBoard;
        $this->playerId = $playerId;
=======
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
    private $tiles;

    public function __construct($name, $population, $size, $buildings, $defense, $attack, $production, $science,$tilePos)
    {
        GameRunner::$gameBoard;
>>>>>>> 202ef54839033042ef5ca7abbb7fe11b4d9c5583
        $this->name = $name;
        $this->population = $population;
        $this->size = $size;
        $this->buildings = $buildings;
        $this->defense = $defense;
        $this->attack = $attack;
        $this->production = $production;
        $this->science = $science;
        $this->tiles = $this::setInitialTiles($tilePos);
<<<<<<< HEAD
        GameRunner::$cities[] = $this;
    }

    /**     * @param $tilePos * @return array
=======
    }

    /**
     * @param $tilePos
>>>>>>> 202ef54839033042ef5ca7abbb7fe11b4d9c5583
     * @return array
     */
    private static function setInitialTiles($tilePos)
    {
        $output = array();
        $output[] = $tilePos;
<<<<<<< HEAD
        $output[] = GameRunner::$gameBoard->findTile($tilePos->getRow() - 1, $tilePos->getCol());
        $output[] = GameRunner::$gameBoard->findTile($tilePos->getRow(), $tilePos->getCol() + 1);
        $output[] = GameRunner::$gameBoard->findTile($tilePos->getRow() + 1, $tilePos->getCol());
        $output[] = GameRunner::$gameBoard->findTile($tilePos->getRow(), $tilePos->getCol() - 1);
=======
        $output[] = GameRunner::$gameBoard->findTile($tilePos->getRow()-1,$tilePos->getCol());
        $output[] = GameRunner::$gameBoard->findTile($tilePos->getRow(),$tilePos->getCol()+1);
        $output[] = GameRunner::$gameBoard->findTile($tilePos->getRow()+1,$tilePos->getCol());
        $output[] = GameRunner::$gameBoard->findTile($tilePos->getRow(),$tilePos->getCol()-1);
>>>>>>> 202ef54839033042ef5ca7abbb7fe11b4d9c5583
        return $output;
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

<<<<<<< HEAD
    /**     * @return mixed */
=======
    /**
     * @return mixed
     */
>>>>>>> 202ef54839033042ef5ca7abbb7fe11b4d9c5583
    public function getTiles()
    {
        return $this->tiles;
    }

    private function checkForDuplicateBuilding($newBuilding)
    {
        foreach ($this->getBuildings() as $building) {
<<<<<<< HEAD
            /** @var Building $building */
            /** @var Building $newBuilding */
=======
>>>>>>> 202ef54839033042ef5ca7abbb7fe11b4d9c5583
            if ($building->getType() == $newBuilding->getType()) {
                return false;
            }
        }
<<<<<<< HEAD
        return true;
=======
>>>>>>> 202ef54839033042ef5ca7abbb7fe11b4d9c5583
    }

    public function getBuildings()
    {
        return $this->buildings;
    }
}