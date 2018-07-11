<<<<<<< HEAD
<?php
/**
 * Created by PhpStorm.
 * User: johnm
 * Date: 5/24/2018
 * Time: 9:30 PM
 */

namespace CivilizationPhp;


class Building
{
    public $type;
    public $addedGold;
    public $addedProduction;
    public $addedDefense;
    public $addedAttack;
    public $upkeep;

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
=======
<?php
/**
 * Created by PhpStorm.
 * User: johnm
 * Date: 5/24/2018
 * Time: 9:30 PM
 */

namespace CivilizationPhp;


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
>>>>>>> 202ef54839033042ef5ca7abbb7fe11b4d9c5583
}