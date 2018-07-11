<<<<<<< HEAD
<?php
/**
 * Created by PhpStorm.
 * User: johnm
 * Date: 5/8/2018
 * Time: 9:02 PM
 */


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
            for ($j = 0; $j < 100; $j++) {
                $this->tiles[$i][$j] = new Tile($i * $this->size, $j * $this->size, $this->size, 0);
            }
        }
    }

    function getImages()
    {
        for ($i = 0; $i < count($this->tiles); $i++) {
            for ($j = 0; $j < count($this->tiles[$i]); $j++) {
                $this->tilesAsNumbers = $this->tilesAsNumbers . $this->tiles[$i][$j]->image . ',';
            }
        }
        $this->tilesAsNumbers = substr($this->tilesAsNumbers,0, strlen($this->tilesAsNumbers));
    }

    function randomize2()
    {
        for ($i = 0; $i < count($this->tiles); $i++) {
            for ($j = 0; $j < count($this->tiles[$i]); $j++) {
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

        $grassToForest = rand(0,4);
        for($i = 0; $i<count($this->tiles);$i++){
            for($j = 0; $j<count($this->tiles[$i]);$j++){
                if($grassToForest == 1&&$this->tiles[$i][$j]->image == 1){
                    $this->tiles[$i][$j]->image = 7;
                }
                $grassToForest = rand(0,4);
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
                $possible = [$i > 0 ? $this->tiles[$i - 1][$j] : -1, $j>0?$this->tiles[$i][$j - 1]:-1, $j<count($this->tiles[$i])-1?$this->tiles[$i][$j + 1]:-1, $i < count($this->tiles)-1 ? $this->tiles[$i + 1][$j] : -1];
                //alert('in possible');
                for ($k = 0; $k < count($possible); $k++) {
                    if (is_numeric($possible[$k])|| !(isset($possible[$k]))) {
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
                $possible = [$i > 0 ? $this->tiles[$i - 1][$j] : -1, $j>0?$this->tiles[$i][$j - 1]:-1, $j<count($this->tiles[$i])-1?$this->tiles[$i][$j + 1]:-1, $i < count($this->tiles)-1 ? $this->tiles[$i + 1][$j] : -1];
                //alert('in possible');
                for ($k = 0; $k < count($possible); $k++) {
                    if (is_numeric($possible[$k])|| !(isset($possible[$k]))) {
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

$board = new BoardOfTiles(32);
$board->randomize2();
$board->getImages();
$outputStream = fopen('../Maps/serverMap.txt', 'w');
$stringFormOfMap = count($board->tiles).','.count($board->tiles[0]). ',' . $board->tilesAsNumbers;
fwrite($outputStream, $stringFormOfMap);
fclose($outputStream);
=======
<?php
/**
 * Created by PhpStorm.
 * User: johnm
 * Date: 5/8/2018
 * Time: 9:02 PM
 */


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
            for ($j = 0; $j < 100; $j++) {
                $this->tiles[$i][$j] = new Tile($i * $this->size, $j * $this->size, $this->size, 0);
            }
        }
    }

    function getImages()
    {
        for ($i = 0; $i < count($this->tiles); $i++) {
            for ($j = 0; $j < count($this->tiles[$i]); $j++) {
                $this->tilesAsNumbers = $this->tilesAsNumbers . $this->tiles[$i][$j]->image . ',';
            }
        }
        $this->tilesAsNumbers = substr($this->tilesAsNumbers,0, strlen($this->tilesAsNumbers));
    }

    function randomize2()
    {
        for ($i = 0; $i < count($this->tiles); $i++) {
            for ($j = 0; $j < count($this->tiles[$i]); $j++) {
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

        $grassToForest = rand(0,4);
        for($i = 0; $i<count($this->tiles);$i++){
            for($j = 0; $j<count($this->tiles[$i]);$j++){
                if($grassToForest == 1&&$this->tiles[$i][$j]->image == 1){
                    $this->tiles[$i][$j]->image = 7;
                }
                $grassToForest = rand(0,4);
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
                $possible = [$i > 0 ? $this->tiles[$i - 1][$j] : -1, $j>0?$this->tiles[$i][$j - 1]:-1, $j<count($this->tiles[$i])-1?$this->tiles[$i][$j + 1]:-1, $i < count($this->tiles)-1 ? $this->tiles[$i + 1][$j] : -1];
                //alert('in possible');
                for ($k = 0; $k < count($possible); $k++) {
                    if (is_numeric($possible[$k])|| !(isset($possible[$k]))) {
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
                $possible = [$i > 0 ? $this->tiles[$i - 1][$j] : -1, $j>0?$this->tiles[$i][$j - 1]:-1, $j<count($this->tiles[$i])-1?$this->tiles[$i][$j + 1]:-1, $i < count($this->tiles)-1 ? $this->tiles[$i + 1][$j] : -1];
                //alert('in possible');
                for ($k = 0; $k < count($possible); $k++) {
                    if (is_numeric($possible[$k])|| !(isset($possible[$k]))) {
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

$board = new BoardOfTiles(32);
$board->randomize2();
$board->getImages();
$outputStream = fopen('../Maps/serverMap.txt', 'w');
$stringFormOfMap = count($board->tiles).','.count($board->tiles[0]). ',' . $board->tilesAsNumbers;
fwrite($outputStream, $stringFormOfMap);
fclose($outputStream);
>>>>>>> 202ef54839033042ef5ca7abbb7fe11b4d9c5583
echo($stringFormOfMap);