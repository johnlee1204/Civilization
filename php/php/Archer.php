<?php
/**
 * Created by PhpStorm.
 * User: johnm
 * Date: 5/27/2018
 * Time: 12:51 AM
 */

namespace CivilizationPhp;


class Archer extends Unit
{
    private $attackRange;
    public function __construct($id,$attack, $defense, $movementPoints, $health, $name, $image, Tile $posTile)
    {
        $this->attackRange = 2;
        parent::__construct($id,$attack, $defense, $movementPoints, $health, $name, $image, $posTile);
    }

    public function attack($enemy)
{
    if(!$this->canShoot($enemy->getPosTile()))
    {
        return;
    }
    $enemy->setHealth($enemy->getHealth() - $this->attack);
}

    public function canShoot($enemyPos)
    {
        $temp = $this->movementPoints;
        $this->movementPoints = $this->attackRange;
        if (!$this->canMove($enemyPos)) {
            $this->movementPoints = $temp;
            return false;
        }
        $this->movementPoints = $temp;
        return true;
    }


}