<?php /** * Created by PhpStorm. * User: johnm * Date: 5/24/2018 * Time: 9:27 PM */

namespace CivilizationPhp;
class Tile
{
    public $x;
    public $y;
    public $size;
    public $image;
    public $row;
    public $col;
    public $isObstruct;

    public function __construct($x, $y, $size, $image, $row, $col, $isObstruct)
    {
        $this->x = $x;
        $this->y = $y;
        $this->size = $size;
        $this->image = $image;
        $this->row = $row;
        $this->col = $col;
        $this->isObstruct;
    }

    public function getRow()
    {
        return $this->row;
    }

    public function getCol()
    {
        return $this->col;
    }

    public function getIsObstruct()
    {
        return $this->isObstruct;
    }
}