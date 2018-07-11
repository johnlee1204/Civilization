<<<<<<< HEAD
<?php /** * Created by PhpStorm. * User: johnm * Date: 5/24/2018 * Time: 9:28 PM */

namespace CivilizationPhp;
include_once('Tile.php');

class BoardOfTiles
{
    public $tiles;
    public $size;
    public $width;
    public $height;
    public $tilesAsNumbers = '';

    public function __construct($width, $height, $size = 32)
    {
        $this->width = $width;
        $this->height = $height;
        $this->size = $size;
        $this->tiles = array();
        for ($i = 0; $i < $this->height; $i++) {
            $this->tiles[$i] = array();
            for ($j = 0; $j < $this->width; $j++) {
                $this->tiles[$i][$j] = new Tile($j * $this->size, $i * $this->size, $this->size, -1, $i, $j, false);
            }
        }
    }

    /**     *     */
    function getImages()
    {
        for ($i = 0; $i < count($this->tiles); $i++) {
            for ($j = 0; $j < count($this->tiles[$i]); $j++) {
                $this->tilesAsNumbers = $this->tilesAsNumbers . $this->tiles[$i][$j]->image . ',';
            }
        }
        $this->tilesAsNumbers = substr($this->tilesAsNumbers, 0, strlen($this->tilesAsNumbers));
    }

    public function randomize2()
    {
        for ($i = 0; $i < count($this->tiles); $i++) {
            for ($j = 0; $j < count($this->tiles[$i]); $j++) {
                $randomTile = rand(1, 20);
                if ($randomTile < 9) $this->tiles[$i][$j]->image = 1; else if ($randomTile < 17) $this->tiles[$i][$j]->image = 3; else if ($randomTile < 18) $this->tiles[$i][$j]->image = 2; else                    $this->tiles[$i][$j]->image = 4;
            }
        }
        $this->smooth();
        $this->smooth();
        $this->smooth();
        $this->smooth();
        for ($i = 0; $i < count($this->tiles); $i++) {
            for ($j = 0; $j < count($this->tiles[$i]); $j++) {
                if ($this->tiles[$i][$j]->image == 3) {
                    $possible = [($i > 0 && $j > 0) ? $this->tiles[$i - 1][$j - 1] : false, $i > 0 ? $this->tiles[$i - 1][$j] : false, ($i > 0 && $j < count($this->tiles[$i]) - 1) ? $this->tiles[$i - 1][$j + 1] : false, $j > 0 ? $this->tiles[$i][$j - 1] : false, $j < count($this->tiles[$i]) - 1 ? $this->tiles[$i][$j + 1] : false, ($i < count($this->tiles) - 1 && $j > 0) ? $this->tiles[$i + 1][$j - 1] : false, $i < count($this->tiles) - 1 ? $this->tiles[$i + 1][$j] : false, ($i < count($this->tiles) - 1 && $j < count($this->tiles[$i]) - 1) ? $this->tiles[$i + 1][$j + 1] : false];
                    for ($k = 0; $k < count($possible); $k++) {
                        if ($possible[$k] === false || !(isset($possible[$k]))) {
                            array_splice($possible, $k, 1);
                            $k--;
                        }
                    }
                    $isDeep = true;
                    for ($p = 0; $p < count($possible); $p++) {
                        if ($possible[$p]->image != 3 && $possible[$p]->image != 6) {
                            $isDeep = false;
                        }
                    }
                    if ($isDeep) $this->tiles[$i][$j]->image = 6;
                }
            }
        }
        for ($i = 0; $i < count($this->tiles); $i++) {
            for ($j = 0; $j < count($this->tiles[$i]); $j++) {
                $random = rand(0, 3);
                if ($random == 1 && $this->tiles[$i][$j]->image == 1) {
                    $this->tiles[$i][$j]->image = 7;
                }
            }
        }
    }

    /**     *     */
    function smooth()
    {
        $gc = 0;
        $wc = 0;
        $rc = 0;
        $sc = 0;
        for ($i = 0; $i < count($this->tiles); $i++) {
            for ($j = 0; $j < count($this->tiles[$i]); $j++) {
                $possible = [$i > 0 ? $this->tiles[$i - 1][$j] : -1, $j > 0 ? $this->tiles[$i][$j - 1] : -1, $j < count($this->tiles[$i]) - 1 ? $this->tiles[$i][$j + 1] : -1, $i < count($this->tiles) - 1 ? $this->tiles[$i + 1][$j] : -1];
                /*alert('in possible');*/
                for ($k = 0; $k < count($possible); $k++) {
                    if (is_numeric($possible[$k]) || !(isset($possible[$k]))) {
                        array_splice($possible, $k, 1);
                        $k--;
                    }
                }
                for ($k = 0; $k < count($possible); $k++) {
                    if ($possible[$k]->image == 1) $gc++; else if ($possible[$k]->image == 3) $wc++; else if ($possible[$k]->image == 4) $sc++; else if ($possible[$k]->image == 2) $rc++;
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
                $possible = [$i > 0 ? $this->tiles[$i - 1][$j] : -1, $j > 0 ? $this->tiles[$i][$j - 1] : -1, $j < count($this->tiles[$i]) - 1 ? $this->tiles[$i][$j + 1] : -1, $i < count($this->tiles) - 1 ? $this->tiles[$i + 1][$j] : -1];
                for ($k = 0; $k < count($possible); $k++) {
                    if (is_numeric($possible[$k]) || !(isset($possible[$k]))) {
                        array_splice($possible, $k, 1);
                        $k--;
                    }
                }
                for ($k = 0; $k < count($possible); $k++) {
                    if ($possible[$k]->image == 1) $gc++; else if ($possible[$k]->image == 3) $wc++; else if ($possible[$k]->image == 4) $sc++; else if ($possible[$k]->image == 2) $rc++;
                }
                if (max($gc, $wc, $sc, $rc) == $gc && max($wc, $sc, $rc) < $gc) $this->tiles[$i][$j]->image = 1;
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

        for($i = 0;$i<count($this->tiles);$i++){
            for($j = 0;$j<count($this->tiles[$i]);$j++){
                if($this->tiles[$i][$j]->image==3||$this->tiles[$i][$j]->image==6){
                    $this->tiles[$i][$j]->isObstruct = true;
                }
            }
        }
    }

    /**     * @param $row * @param $col     * @return Mixed
     * @return bool
     */
    public function findTile($row, $col)
    {
        if ($row < count($this->tiles) && $col < count($this->tiles[0])) {
            return $this->tiles[$row][$col];
        }
        return false;
    }

    public function findTileSafe($row, $col)
    {
        if ($this->tiles[$row][$col]->image != 3 && $this->tiles[$row][$col]->image != 6 && $this->tiles[$row + 1][$col + 1]->image != 3 && $this->tiles[$row + 1][$col + 1]->image != 6) {
            return $this->tiles[$row][$col];
        }
        return $this->findTileSafe(rand(0, $this->getHeight() - 2), rand(0, $this->getWidth() - 2));
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function __toString()
    {
        return ('toString');
    }
}
=======
<?php/** * Created by PhpStorm. * User: johnm * Date: 5/24/2018 * Time: 9:28 PM */namespace CivilizationPhp;include_once('Tile.php');class BoardOfTiles{    public $tiles;    public $size;    public $width;    public $height;    public $tilesAsNumbers = '';    public function __construct($width, $height, $size = 32)    {        $this->width = $width;        $this->height = $height;        $this->size = $size;        $this->tiles = array();        for ($i = 0; $i < $this->height; $i++) {            $this->tiles[$i] = array();            for ($j = 0; $j < $this->width; $j++) {                $this->tiles[$i][$j] = new Tile($j * $this->size, $i * $this->size, $this->size, -1, $i, $j, false);            }        }    }    /**     *     */    function getImages()    {        for ($i = 0; $i < count($this->tiles); $i++) {            for ($j = 0; $j < count($this->tiles[$i]); $j++) {                $this->tilesAsNumbers = $this->tilesAsNumbers . $this->tiles[$i][$j]->image . ',';            }        }        $this->tilesAsNumbers = substr($this->tilesAsNumbers, 0, strlen($this->tilesAsNumbers));    }    public function randomize2()    {        for ($i = 0; $i < count($this->tiles); $i++) {            for ($j = 0; $j < count($this->tiles[$i]); $j++) {                $randomTile = rand(1, 20);                if ($randomTile < 9)                    $this->tiles[$i][$j]->image = 1;                else if ($randomTile < 17)                    $this->tiles[$i][$j]->image = 3;                else if ($randomTile < 18)                    $this->tiles[$i][$j]->image = 2;                else                    $this->tiles[$i][$j]->image = 4;            }        }        $this->smooth();        $this->smooth();        $this->smooth();        $this->smooth();        for ($i = 0; $i < count($this->tiles); $i++) {            for ($j = 0; $j < count($this->tiles[$i]); $j++) {                if ($this->tiles[$i][$j]->image == 3) {                    $possible = [($i > 0 && $j > 0) ? $this->tiles[$i - 1][$j - 1] : false, $i > 0 ? $this->tiles[$i - 1][$j] : false, ($i > 0 && $j < count($this->tiles[$i]) - 1) ? $this->tiles[$i - 1][$j + 1] : false, $j > 0 ? $this->tiles[$i][$j - 1] : false, $j < count($this->tiles[$i]) - 1 ? $this->tiles[$i][$j + 1] : false, ($i < count($this->tiles) - 1 && $j > 0) ? $this->tiles[$i + 1][$j - 1] : false, $i < count($this->tiles) - 1 ? $this->tiles[$i + 1][$j] : false, ($i < count($this->tiles) - 1 && $j < count($this->tiles[$i]) - 1) ? $this->tiles[$i + 1][$j + 1] : false];                    for ($k = 0; $k < count($possible); $k++) {                        if ($possible[$k] === false || !(isset($possible[$k]))) {                            array_splice($possible, $k, 1);                            $k--;                        }                    }                    $isDeep = true;                    for ($p = 0; $p < count($possible); $p++) {                        if ($possible[$p]->image != 3 && $possible[$p]->image != 6) {                            $isDeep = false;                        }                    }                    if ($isDeep)                        $this->tiles[$i][$j]->image = 6;                }            }        }    }    /**     *     */    function smooth()    {        $gc = 0;        $wc = 0;        $rc = 0;        $sc = 0;        for ($i = 0; $i < count($this->tiles); $i++) {            for ($j = 0; $j < count($this->tiles[$i]); $j++) {                $possible = [$i > 0 ? $this->tiles[$i - 1][$j] : -1, $j > 0 ? $this->tiles[$i][$j - 1] : -1, $j < count($this->tiles[$i]) - 1 ? $this->tiles[$i][$j + 1] : -1, $i < count($this->tiles) - 1 ? $this->tiles[$i + 1][$j] : -1];                //alert('in possible');                for ($k = 0; $k < count($possible); $k++) {                    if (is_numeric($possible[$k]) || !(isset($possible[$k]))) {                        array_splice($possible, $k, 1);                        $k--;                    }                }                for ($k = 0; $k < count($possible); $k++) {                    if ($possible[$k]->image == 1)                        $gc++;                    else if ($possible[$k]->image == 3)                        $wc++;                    else if ($possible[$k]->image == 4)                        $sc++;                    else if ($possible[$k]->image == 2)                        $rc++;                }                if (max($gc, $wc, $sc, $rc) == $gc && max($wc, $sc, $rc) < $gc)                    $this->tiles[$i][$j]->image = 1;                else if (max($gc, $wc, $sc, $rc) == $wc && max($gc, $sc, $rc) < $wc)                    $this->tiles[$i][$j]->image = 3;                else if (max($gc, $wc, $sc, $rc) == $sc && max($wc, $gc, $rc) < $sc)                    $this->tiles[$i][$j]->image = 4;                else if (max($gc, $wc, $sc, $rc) == $rc && max($wc, $sc, $gc) < $rc)                    $this->tiles[$i][$j]->image = 2;                $gc = 0;                $wc = 0;                $rc = 0;                $sc = 0;            }        }        for ($i = count($this->tiles) - 1; $i >= 0; $i--) {            for ($j = count($this->tiles[$i]) - 1; $j >= 0; $j--) {                $possible = [$i > 0 ? $this->tiles[$i - 1][$j] : -1, $j > 0 ? $this->tiles[$i][$j - 1] : -1, $j < count($this->tiles[$i]) - 1 ? $this->tiles[$i][$j + 1] : -1, $i < count($this->tiles) - 1 ? $this->tiles[$i + 1][$j] : -1];                //alert('in possible');                for ($k = 0; $k < count($possible); $k++) {                    if (is_numeric($possible[$k]) || !(isset($possible[$k]))) {                        array_splice($possible, $k, 1);                        $k--;                    }                }                for ($k = 0; $k < count($possible); $k++) {                    if ($possible[$k]->image == 1)                        $gc++;                    else if ($possible[$k]->image == 3)                        $wc++;                    else if ($possible[$k]->image == 4)                        $sc++;                    else if ($possible[$k]->image == 2)                        $rc++;                }                if (max($gc, $wc, $sc, $rc) == $gc && max($wc, $sc, $rc) < $gc)                    $this->tiles[$i][$j]->image = 1;                else if (max($gc, $wc, $sc, $rc) == $wc && max($gc, $sc, $rc) < $wc)                    $this->tiles[$i][$j]->image = 3;                else if (max($gc, $wc, $sc, $rc) == $sc && max($wc, $gc, $rc) < $sc)                    $this->tiles[$i][$j]->image = 4;                else if (max($gc, $wc, $sc, $rc) == $rc && max($wc, $sc, $gc) < $rc)                    $this->tiles[$i][$j]->image = 2;                $gc = 0;                $wc = 0;                $rc = 0;                $sc = 0;            }        }    }    /**     * @param $row     * @param $col     * @return Mixed     */    public function findTile($row, $col)    {        for ($i = 0; $i < count($this->tiles); $i++) {            for ($j = 0; $j < count($this->tiles[$i]); $j++)                if ($this->tiles[$i][$j]->getRow() == $row && $this->tiles[$i][$j]->getCol() == $col) {                    return $this->tiles[$i][$j];                }        }        return false;    }    public function getWidth()    {        return $this->width;    }    public function getHeight()    {        return $this->height;    }    public function getSize()    {        return $this->size;    }    public function __toString()    {        return ('toString');    }}
>>>>>>> 202ef54839033042ef5ca7abbb7fe11b4d9c5583
