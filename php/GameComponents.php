<?php


class Tile
{
    public $x;
    public $y;
    public $size;
    public $image;

    function __construct($x, $y, $size, $image)
    {
        $this->x = $x;
        $this->y = $y;
        $this->size = $size;
        $this->image = $image;
    }


}

class BoardOfTiles
{
    public $tiles;
    public $size;
    public $tilesAsNumbers = '';

    function __construct($size)
    {
        $this->$size = $size;
        $this->tiles = array();
        for ($i = 0; $i < 50; $i++) {
            $this->tiles[$i] = array();
            for ($j = 0; $j < 50; $j++) {
                $this->tiles[$i][$j] = new Tile($i * $this->size, $j * $this->size, $this->size, 0);
            }
        }
    }

    /**
     *
     */
    function getImages()
    {
        for ($i = 0; $i < count($this->tiles); $i++) {
            for ($j = 0; $j < count($this->tiles[$i]); $j++) {
                $this->tilesAsNumbers = $this->tilesAsNumbers . $this->tiles[$i][$j]->image . ',';
            }
        }
        $this->tilesAsNumbers = substr($this->tilesAsNumbers, 0, strlen($this->tilesAsNumbers));
    }

    function randomize2()
    {
        $randomTile = rand(1, 10);
        for ($i = 0; $i < count($this->tiles); $i++) {
            for ($j = 0; $j < count($this->tiles); $j++) {
                $randomTile = rand(1, 20);
                if ($randomTile < 9)
                    $this->tiles[$i][$j]->image = 1;
                else if ($randomTile < 17)
                    $this->tiles[$i][$j]->image = 3;
                else if ($randomTile < 18)
                    $this->tiles[$i][$j]->image = 2;
                else
                    $this->tiles[$i][$j]->image = 4;
            }
        }
        $this->smooth();
        $this->smooth();
        $this->smooth();
        $this->smooth();
        
         for($i = 0; $i < count($this->tiles);$i++) {
          for($j = 0; $j<count($this->tiles[$i]);$j++) {
            if($this->tiles[$i][$j]->image==3){
            $possible = [($i>0&&$j>0)?$this->tiles[$i-1][$j-1]:false,$i>0?$this->tiles[$i-1][$j]:false,($i>0&&$j<count($this->tiles)-1)?$this->tiles[$i-1][$j+1]:false,$j>0?$this->tiles[$i][$j-1]:false,$j<count($this->tiles[$i])-1?$this->tiles[$i][$j+1]:false,($i<count($this->tiles)-1&&$j>0)?$this->tiles[$i+1][$j-1]:false,$i<count($this->tiles)-1?$this->tiles[$i+1][$j]:false,($i<count($this->tiles)-1&&$j<count($this->tiles)-1)?$this->tiles[$i+1][$j+1]:false];
            
            for($k = 0; $k<count($possible);$k++) {
              if($possible[$k]===false || !(isset($possible[$k]))) {
                array_splice($possible,$k,1);
                $k--;
              }
            }
            $isDeep = true;
            for($p = 0; $p<count($possible);$p++) {
              if($possible[$p]->image!=3&&$possible[$p]->image!=6) {
                $isDeep = false;
              }
            }
            if($isDeep)
              $this->tiles[$i][$j]->image = 6;
            }
          }
        }
    }

    function smooth()
    {
        $gc = 0;
        $wc = 0;
        $rc = 0;
        $sc = 0;
        for ($i = 0; $i < count($this->tiles); $i++) {
            for ($j = 0; $j < count($this->tiles[$i]); $j++) {
                $possible = [$i > 0 ? $this->tiles[$i - 1][$j] : -1, $j > 0 ? $this->tiles[$i][$j - 1] : -1, $j < count($this->tiles) - 1 ? $this->tiles[$i][$j + 1] : -1, $i < count($this->tiles) - 1 ? $this->tiles[$i + 1][$j] : -1];
                //alert('in possible');
                for ($k = 0; $k < count($possible); $k++) {
                    if (is_numeric($possible[$k]) || !(isset($possible[$k]))) {
                        array_splice($possible, $k, 1);
                        $k--;
                    }
                }
                for ($k = 0; $k < count($possible); $k++) {
                    if ($possible[$k]->image == 1)
                        $gc++;
                    else if ($possible[$k]->image == 3)
                        $wc++;
                    else if ($possible[$k]->image == 4)
                        $sc++;
                    else if ($possible[$k]->image == 2)
                        $rc++;
                }
                if (max($gc, $wc, $sc, $rc) == $gc && max($wc, $sc, $rc) < $gc)
                    $this->tiles[$i][$j]->image = 1;
                else if (max($gc, $wc, $sc, $rc) == $wc && max($gc, $sc, $rc) < $wc)
                    $this->tiles[$i][$j]->image = 3;
                else if (max($gc, $wc, $sc, $rc) == $sc && max($wc, $gc, $rc) < $sc)
                    $this->tiles[$i][$j]->image = 4;
                else if (max($gc, $wc, $sc, $rc) == $rc && max($wc, $sc, $gc) < $rc)
                    $this->tiles[$i][$j]->image = 2;
                $gc = 0;
                $wc = 0;
                $rc = 0;
                $sc = 0;
            }
        }


        for ($i = count($this->tiles) - 1; $i >= 0; $i--) {
            for ($j = count($this->tiles[$i]) - 1; $j >= 0; $j--) {
                $possible = [$i > 0 ? $this->tiles[$i - 1][$j] : -1, $j > 0 ? $this->tiles[$i][$j - 1] : -1, $j < count($this->tiles) - 1 ? $this->tiles[$i][$j + 1] : -1, $i < count($this->tiles) - 1 ? $this->tiles[$i + 1][$j] : -1];
                //alert('in possible');
                for ($k = 0; $k < count($possible); $k++) {
                    if (is_numeric($possible[$k]) || !(isset($possible[$k]))) {
                        array_splice($possible, $k, 1);
                        $k--;
                    }
                }
                for ($k = 0; $k < count($possible); $k++) {
                    if ($possible[$k]->image == 1)
                        $gc++;
                    else if ($possible[$k]->image == 3)
                        $wc++;
                    else if ($possible[$k]->image == 4)
                        $sc++;
                    else if ($possible[$k]->image == 2)
                        $rc++;
                }
                if (max($gc, $wc, $sc, $rc) == $gc && max($wc, $sc, $rc) < $gc)
                    $this->tiles[$i][$j]->image = 1;
                else if (max($gc, $wc, $sc, $rc) == $wc && max($gc, $sc, $rc) < $wc)
                    $this->tiles[$i][$j]->image = 3;
                else if (max($gc, $wc, $sc, $rc) == $sc && max($wc, $gc, $rc) < $sc)
                    $this->tiles[$i][$j]->image = 4;
                else if (max($gc, $wc, $sc, $rc) == $rc && max($wc, $sc, $gc) < $rc)
                    $this->tiles[$i][$j]->image = 2;
                $gc = 0;
                $wc = 0;
                $rc = 0;
                $sc = 0;
            }
        }


    }


}

function createMap($size){
  $board = new BoardOfTiles($size);
  $serializedBoard = serialize($board);
  $outputStream = fopen("Maps/board.txt","w");
  fwrite($outputStream,$serializedBoard);
  fclose($outputStream);
}

class Player
{
    private $name;
    private $nationName;
    private $money;
    private $units;
    private $nationPicture;

    function __construct($name, $nationName, $nationPicture)
    {
        $this->name = $name;
        $this->nationName = $nationName;
        $this->units = [];
        $this->units[] = new Warrior();
        $this->units[] = new Settler();
        $this->nationPicture = $nationPicture;
    }
}

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


class Building
{
    private $type;
    private $addedGold;
    private $addedProduction;
    private $addedDefense;
    private $addedAttack;
    private $upkeep;

    public function __construct($type, $addedGold, $addedProduction, $addedDefense, $addedAttack, $upkeep)
    {
        $this->type = $type;
        $this->addedGold = $addedGold;
        $this->addedProduction = $addedProduction;
        $this->addedDefense = $addedDefense;
        $this->addedAttack = $addedAttack;
        $this->upkeep = $upkeep;
    }

    /**
     * @return string
     */
    public function getType(){
        return $this->type;
    }
}

$function = $_GET['functionname'];
$params = $_GET['size'];
if($function=='createMap')
  createMap($params);
  