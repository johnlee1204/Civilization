<?php
use CivilizationPhp\Unit;
use CivilizationPhp\BoardOfTiles;
use CivilizationPhp\Player;
include_once('BoardOfTiles.php');
include_once('Player.php');
include_once('Unit.php');
class GameRunner
{
	private static $nextTurn = false;
	private static $unitId;
	public static $gameBoard;
	public static $player1;
	public static $units;
	static function start()
	{
		GameRunner::$units = array();
		GameRunner::$gameBoard = new BoardOfTiles(100, 50, 32);
		GameRunner::$gameBoard->randomize2();
		GameRunner::$player1 = new Player('John', 'Johnistan', 'images/johnPlayerImage');
	}
	public static function getUnitId()
	{
		GameRunner::$unitId++;
		return GameRunner::$unitId - 1;
	}
	public static function initiate()
	{
		$servername = "localhost";
		$username = "johntheadmin";
		$password = "Echo120499!";
		$dbname = "civilizationInformation";
		$conn = new mysqli($servername, $username, $password, $dbname);
		$conn->query("DELETE FROM BoardOfTiles WHERE 1");
		$conn->query("DELETE FROM Buildings WHERE 1");
		$conn->query("DELETE FROM Cities WHERE 1");
		$conn->query("DELETE FROM Players WHERE 1");
		$conn->query("DELETE FROM Units WHERE 1");
		$conn->query("ALTER TABLE BoardOfTiles AUTO_INCREMENT = 0");
		$conn->query("ALTER TABLE Buildings AUTO_INCREMENT = 0");
		$conn->query("ALTER TABLE Cities AUTO_INCREMENT = 0");
		$conn->query("ALTER TABLE Players AUTO_INCREMENT = 0");
		$conn->query("ALTER TABLE Units AUTO_INCREMENT = 0");
		foreach (GameRunner::$gameBoard->tiles as $row) {
			foreach ($row as $tile) {
				$sql = "
                INSERT INTO BoardOfTiles(x,y,size,image,row,col,isObstruct,width,height)
                VALUES ('{$tile->x}','{$tile->y}','{$tile->size}','{$tile->image}','{$tile->row}','{$tile->col}',false,'" . 100 . "','" . 50 . "')
                ";
				if (!$conn->query($sql)) {
					echo("Error" . $sql . $conn->error);
				}
			}
		}
		$sql = "
        INSERT INTO Players(nationName,money)
        VALUES('" . GameRunner::$player1->nationName . "','" . GameRunner::$player1->money . "')
        ";
		if (!$conn->query($sql)) {
			echo("Error" . $sql . $conn->error);
		}
		foreach (GameRunner::$units as $unit) {
			$sql = "
            INSERT INTO Units(unitId,playerId,attack,defense,movementPoints,health,name,image,row,col)
            VALUES('{$unit->id}','0','{$unit->attack}','{$unit->defense}','{$unit->movementPoints}','{$unit->health}','{$unit->name}','{$unit->image}','{$unit->posTile->row}','{$unit->posTile->col}')
            ";
			if (!$conn->query($sql)) {
				echo("Error" . $sql . $conn->error);
			}
		}
		$conn->close();
		if (GameRunner::$nextTurn) {
			$output = json_encode(array('player1' => GameRunner::$player1, 'gameboard' => GameRunner::$gameBoard, 'units' => GameRunner::$units));
			echo $output;
		}
	}
	public static function saveState()
	{
		$servername = "localhost";
		$username = "johntheadmin";
		$password = "Echo120499!";
		$dbname = "civilizationInformation";
		$conn = new mysqli($servername, $username, $password, $dbname);
		$count = 1;
		foreach (GameRunner::$gameBoard->tiles as $row) {
			foreach ($row as $tile) {
				$sql = "
                UPDATE BoardOfTiles
                SET
                x = '{$tile->x}',
                y = '{$tile->y}',
                size = '{$tile->size}',
                image = '{$tile->image}',
                row = '{$tile->row}',
                col = '{$tile->col}',
                isObstruct = false,
                width = '100',
                height = '50'
                WHERE id = {$count}
                ";
				$count++;
				if (!$conn->query($sql)) {
					echo("Error" . $sql . $conn->error);
				}
			}
		}
		$sql = "
        UPDATE Players
        SET
        nationName = '" . GameRunner::$player1->nationName . "',
        money = '" . GameRunner::$player1->money . "'
        ";
		if (!$conn->query($sql)) {
			echo("Error" . $sql . $conn->error);
		}
		$count = 1;
		foreach (GameRunner::$units as $unit) {
			$sql = "
            UPDATE Units 
			SET
            unitId = '{$unit->id}',
            playerId = '0',
            attack = '{$unit->attack}',
            defense = '{$unit->defense}',
            movementPoints = '{$unit->movementPoints}',
            health = '{$unit->health}',
            name = '{$unit->name}',
            image = '{$unit->image}',
            row = '{$unit->posTile->row}',
            col = '{$unit->posTile->col}'
            WHERE id = {$count}
            ";
			$count++;
			if (!$conn->query($sql)) {
				echo("Error" . $sql . $conn->error);
			}
		}
		$conn->close();
		if (GameRunner::$nextTurn) {
			$output = json_encode(array('player1' => GameRunner::$player1, 'gameboard' => GameRunner::$gameBoard, 'units' => GameRunner::$units));
			echo $output;
		}
	}
	public static function loadState()
	{
		$servername = "localhost";
		$username = "johntheadmin";
		$password = "Echo120499!";
		$dbname = "civilizationInformation";
		$conn = new mysqli($servername, $username, $password, $dbname);
		$sql = "
        SELECT * FROM BoardOfTiles
        ";
		$board = [];
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$board[] = $row;
		}
		//die($board);
		$count = 0;
		GameRunner::$gameBoard->width = $board[0]->width;
		GameRunner::$gameBoard->height = $board[0]->height;
		foreach (GameRunner::$gameBoard->tiles as $row) {
			foreach ($row as $tile) {
				$tile->x = $board[$count]['x'];
				$tile->y = $board[$count]['y'];
				$tile->size = $board[$count]['size'];
				$tile->image = $board[$count]['image'];
				$tile->row = $board[$count]['row'];
				$tile->col = $board[$count]['col'];
				$tile->isObstruct = $board[$count]['isObstruct'];
				$count++;
			}
		}
		$sql = "
        SELECT * FROM Players
        ";
		$player = [];
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$player[] = $row;
		}
		GameRunner::$player1->nationName = $player[0]['nationName'];
		GameRunner::$player1->money = $player[0]['money'];
		$sql = "
        SELECT * FROM Units
        ";
		$units = [];
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$units[] = $row;
		}
		$count = 0;
		foreach (GameRunner::$units as $unit) {
			$unit->unitId = $units[$count]['unitId'];
			$unit->playerId = $units[$count]['playerId'];
			$unit->attack = $units[$count]['attack'];
			$unit->defense = $units[$count]['defense'];
			$unit->movementPoints = $units[$count]['movementPoints'];
			$unit->health = $units[$count]['health'];
			$unit->name = $units[$count]['name'];
			$unit->image = $units[$count]['image'];
			$unit->posTile->row = $units[$count]['row'];
			$unit->posTile->col = $units[$count]['col'];
			$count++;
		}
		$conn->close();
	}
	public static function setNextTurn($bool)
	{
		GameRunner::$nextTurn = $bool;
	}
}
$gameRunner = NULL;
$servername = "localhost";
$username = "johntheadmin";
$password = "Echo120499!";
$dbname = "civilizationInformation";
$conn = new mysqli($servername, $username, $password, $dbname);
$sql = "SELECT COUNT(*) FROM BoardOfTiles WHERE 1";
$count = mysqli_fetch_assoc($conn->query($sql));
$count = ($count['COUNT(*)']);
$conn->close();
if (isset($_GET['start']) && $count < 1) {
	$gameRunner = new GameRunner();
	$gameRunner::start();
} else {
	$gameRunner = new GameRunner();
	$gameRunner::start();
	$gameRunner::loadState();
}
if (isset($_GET['move'])) {
	$id = $_GET['unitId'];
	$newRow = $_GET['newRow'];
	$newCol = $_GET['newCol'];
	/** @var Unit $unit */
	Unit::getUnitById($id)->move(GameRunner::$gameBoard->findTile($newRow, $newCol));
	GameRunner::$player1->units[$id] = Unit::getUnitById($id);
	echo json_encode(Unit::getUnitById($id)->getPosTile());
}
if (isset($_GET['nextturn'])) {
	GameRunner::setNextTurn(true);
} else {
	GameRunner::setNextTurn(false);
}
if ($count > 0) {
	$gameRunner::saveState();
} else {
	$gameRunner::initiate();
}