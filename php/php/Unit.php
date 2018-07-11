<?php /** * Created by PhpStorm. * User: johnm * Date: 5/24/2018 * Time: 10:04 PM */
namespace CivilizationPhp;
use GameRunner;
abstract class Unit
{
	public $id;
	public $attack;
	public $defense;
	public $movementPoints;
	public $health;
	public $name;
	public $image;
	public $posTile;
	public static $allUnits;
	/**     * Unit constructor.     *
	 * @param $id
	 * @param $attack
	 * @param $defense
	 * @param $movementPoints
	 * @param $health
	 * @param $name
	 * @param $image
	 * @param $posTile
	 */
	public function __construct($id, $attack, $defense, $movementPoints, $health, $name, $image, $posTile)
	{
		$this->id = $id;
		$this->attack = $attack;
		$this->defense = $defense;
		$this->movementPoints = $movementPoints;
		$this->health = $health;
		$this->name = $name;
		$this->image = $image;
		$this->posTile = $posTile;
	}
	public function getId()
	{
		return $this->id;
	}
	public function getAttack()
	{
		return $this->attack;
	}
	public function getDefense()
	{
		return $this->defense;
	}
	public function getMovementPoints()
	{
		return $this->movementPoints;
	}
	public function getHealth()
	{
		return $this->health;
	}
	public function getName()
	{
		return $this->name;
	}
	public function getImage()
	{
		return $this->image;
	}
	public function getPosTile()
	{
		return $this->posTile;
	}
	public function setAttack($attack)
	{
		$this->attack = $attack;
	}
	public function setDefense($defense)
	{
		$this->defense = $defense;
	}
	public function setMovementPoints($movementPoints)
	{
		$this->movementPoints = $movementPoints;
	}
	public function setHealth($health)
	{
		$this->health = $health;
	}
	public function setName($name)
	{
		$this->name = $name;
	}
	public function setImage($image)
	{
		$this->image = $image;
	}
	public function setPosTile($posTile)
	{
		$this->posTile = $posTile;
	}
	public function move($newTile)
	{
		if ($this->canMove($newTile)) $this->posTile = $newTile;
	}
	/**     * @param Tile $newTile * @return bool
	 * @return bool
	 */
	public function canMove($newTile)
	{
		if ($newTile->getIsObstruct()) {
			return false;
		}
		$columnDistance = abs($newTile->getCol() - $this->posTile->getCol());
		$rowDistance = abs($newTile->getRow() - $this->posTile->getRow());
		$tileDistance = $columnDistance + $rowDistance;
		if ($tileDistance <= $this->movementPoints) {
			return true;
		} else {
			return false;
		}
	}
	/**     * @param Unit $enemy */
	public function attack($enemy)
	{
		if (!$this->canAttack($enemy->getPosTile())) {
			return;
		}
		$enemy->setHealth($enemy->getHealth() - $this->attack);
		$this->health = $this->health - $enemy->getDefense();
	}
	private function canAttack($enemyPos)
	{
		$temp = $this->movementPoints;
		$this->movementPoints = 1;
		if (!$this->canMove($enemyPos)) {
			$this->movementPoints = $temp;
			return false;
		}
		$this->movementPoints = $temp;
		return true;
	}
	public static function getUnitById($id)
	{
		return GameRunner::$units[$id];
	}
}