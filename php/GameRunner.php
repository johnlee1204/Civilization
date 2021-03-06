<?php

use CivilizationPhp\BoardOfTiles;
use CivilizationPhp\Player;
use CivilizationPhp\Settler;
use CivilizationPhp\Warrior;
use CivilizationPhp\Unit;
use CivilizationPhp\City;


include_once('BoardOfTiles.php');
include_once('Player.php');
include_once('Unit.php');
include_once('Tile.php');
include_once('Warrior.php');
include_once('Settler.php');
include_once('City.php');

class GameRunner
{
    private static $nextTurn = false;
    private static $unitId;
    public static $gameBoard;
    public static $player1;
    public static $player2;
    public static $player3;
    public static $players;
    public static $units;
    public static $cities;


    static function start()
    {
        GameRunner::$units = array();
        GameRunner::$players = array();
        GameRunner::$cities = array();
        GameRunner::$gameBoard = new BoardOfTiles(100, 50, 32);
        GameRunner::$gameBoard->randomize2();
        GameRunner::$player1 = new Player(1, 'John', 'Johnistan', 'images/johnPlayerImage');
        GameRunner::$player2 = new Player(2, 'Logan', 'Loganistan', 'images/loganPlayerImage');
        GameRunner::$player3 = new Player(3, 'Chris', 'Kingdom Of Chris', 'images/chrisPlayerImage');
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
                INSERT INTO BoardOfTiles(x,y,size,image,row,col,isObstruct,width,height,occupied)
                VALUES ('{$tile->x}','{$tile->y}','{$tile->size}','{$tile->image}','{$tile->row}','{$tile->col}',false,'" . 100 . "','" . 50 . "',false)
                ";
                if (!$conn->query($sql)) {
                    echo("Error" . $sql . $conn->error);
                }
            }
        }
        foreach (GameRunner::$players as $player) {
            $sql = "
        INSERT INTO Players(nationName,money)
        VALUES('" . $player->nationName . "','" . $player->money . "')
        ";
            if (!$conn->query($sql)) {
                echo("Error" . $sql . $conn->error);
            }
        }
        foreach (GameRunner::$units as $unit) {
            $sql = "
            INSERT INTO Units(unitId,playerId,attack,defense,movementPoints,health,name,image,row,col)
            VALUES('{$unit->id}','{$unit->playerId}','{$unit->attack}','{$unit->defense}','{$unit->movementPoints}','{$unit->health}','{$unit->name}','{$unit->image}','{$unit->posTile->row}','{$unit->posTile->col}')
            ";
            if (!$conn->query($sql)) {
                echo("Error" . $sql . $conn->error);
            }
        }
        $conn->close();
        if (GameRunner::$nextTurn) {
            $outputArray = [];
            $playerCount = 1;
            foreach (GameRunner::$players as $player) {
                $temp = "player" . $playerCount;
                $outputArray[$temp] = $player;
                $playerCount++;
            }
            $outputArray['gameBoard'] = GameRunner::$gameBoard;
            $outputArray['units'] = GameRunner::$units;
            $output = json_encode($outputArray);
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
                /*$sql = "SELECT COUNT(*) FROM BoardOfTiles
                WHERE id = {$count}
                ";
                if(mysqli_fetch_assoc($conn->query($sql))!=1){
                    $count++;
                }*/
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
        foreach (GameRunner::$players as $player) {
            $sql = "
        UPDATE Players
        SET
        nationName = '" . $player->nationName . "',
        money = '" . $player->money . "'
        ";
            if (!$conn->query($sql)) {
                echo("Error" . $sql . $conn->error);
            }
        }
        $count = 1;
        $forCount = 0;
		/************************************************************************************************************************/
        foreach (GameRunner::$units as $unit) {
            if ($unit->playerId > count(GameRunner::$players)) {
                unset(GameRunner::$units[$forCount]);
            }
            while(true) {
				$sql = "SELECT COUNT(*) FROM Units
					WHERE id = {$count}
					";
				if (mysqli_fetch_array($conn->query($sql))[0] != '1') {
					$count++;
				}
				else{
					break;
				}
			}
            $sql = "
            UPDATE Units 
			SET
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
            $forCount++;
        }

        /************************************************************************************************************************/
        $count = 1;
        foreach (GameRunner::$cities as $city) {
            $sql = "SELECT COUNT(*) FROM Cities
            WHERE id = {$count}
            ";
            if (mysqli_fetch_assoc($conn->query($sql)) != 1) {
                $count++;
            }
            $sql = "
            UPDATE Cities 
			SET
            name = '{$city->name}',
            population = '{$city->population}',
            size = '{$city->size}',
            defense = '{$city->defense}',
            attack = '{$city->attack}',
            production = '{$city->production}',
            science = '{$city->science}',
            row = '{$city->posTile->row}',
            col = '{$city->posTile->col}'
            WHERE id = {$count}
            ";
            $count++;
            if (!$conn->query($sql)) {
                echo("Error" . $sql . $conn->error);
            }
        }


        $conn->close();
        if (GameRunner::$nextTurn) {
            $outputArray = [];
            $playerCount = 1;
            foreach (GameRunner::$players as $player) {
                $temp = "player" . $playerCount;
                $outputArray[$temp] = $player;
                $playerCount++;
            }
            $outputArray['gameBoard'] = GameRunner::$gameBoard;
            $outputArray['units'] = GameRunner::$units;
            $outputArray['cities'] = GameRunner::$cities;
            $output = json_encode($outputArray);
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
        $playerCount = 0;
        foreach ($player as $play) { //update for every player
            GameRunner::$players[$playerCount]->nationName = $play['nationName'];
            GameRunner::$players[$playerCount]->money = $play['money'];
            $playerCount++;
        }
        $sql = "
        SELECT * FROM Units
        ";
        $units = [];
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $units[] = $row;
        }
        GameRunner::$units = array();
        foreach (GameRunner::$players as $player){
            $player->units = array();
        }

        foreach ($units as $unit) {
            if ($unit['image'] == 0) {
                GameRunner::$players[$unit['playerId']-1]->units[] =  new \CivilizationPhp\Warrior($unit['playerId'], $unit['unitId'], $unit['attack'], $unit['defense'], $unit['movementPoints'], $unit['health'], $unit['name'], $unit['image'], GameRunner::$gameBoard->findTile($unit['row'], $unit['col']));
            } else if ($unit['image'] == 1) {
                GameRunner::$players[$unit['playerId']-1]->units[] = new \CivilizationPhp\Settler($unit['playerId'], $unit['unitId'], $unit['attack'], $unit['defense'], $unit['movementPoints'], $unit['health'], $unit['name'], $unit['image'], GameRunner::$gameBoard->findTile($unit['row'], $unit['col']));
            }
        }
        $sql = "
        SELECT * FROM Cities
        ";
        $citiesT = [];
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $citiesT[] = $row;
        }

        foreach ($citiesT as $city) {
            new \CivilizationPhp\City($city['playerId'], $city['name'], $city['population'], $city['size'],'', $city['defense'], $city['attack'], $city['production'], $city['science'], GameRunner::$gameBoard->findTile($city['row'], $city['col']));
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

if (isset($_GET['resetgame'])) {
    $conn->query("DELETE FROM BoardOfTiles WHERE 1");
    $conn->close();
    echo("Success: true");
    die();
}

if (isset($_GET['foundcity'])) {
    $settlerId = $_GET['settlerid'];
    $sql = "
    SELECT * FROM Units
    WHERE unitId = {$settlerId}
    ";
    $setter = mysqli_fetch_assoc($conn->query($sql));
    $sql = "
    INSERT INTO Cities(playerId,name,population,size,defense,attack,production,science,row,col)
    VALUES({$_GET['playerid']},'{$_GET['name']}',1,1,100,50,5,5,{$setter['row']},{$setter['col']})
    ";
    if (!$conn->query($sql)) {
        echo("Error" . $sql . $conn->error);
    }
    $sql = "
    DELETE FROM Units
    WHERE unitId = {$settlerId}
    ";
    $conn->query($sql);
    $sql = "
    UPDATE BoardOfTiles
    SET
    image = 5 
    WHERE 
    row = {$setter['row']}
    AND 
    col = {$setter['col']}
    ";
    $conn->query($sql);
}

$sql = "SELECT COUNT(*) FROM BoardOfTiles WHERE 1";
$count = mysqli_fetch_assoc($conn->query($sql));
$count = ($count['COUNT(*)']);
$conn->close();
if (isset($_GET['start']) && $count < 1) {
    $gameRunner = new GameRunner();
    $gameRunner::start();
} else if (!isset($_GET['updatemovement']) && !isset($_GET['move']) && !isset($_GET['attack'])) {
    $gameRunner = new GameRunner();
    $gameRunner::start();
    $gameRunner::loadState();
}


if (isset($_GET['move'])) {
    $id = intval($_GET['unitId']);
    $newRow = $_GET['newRow'];
    $newCol = $_GET['newCol'];
    $servername = "localhost";
    $username = "johntheadmin";
    $password = "Echo120499!";
    $dbname = "civilizationInformation";
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "
	SELECT * FROM Units
	WHERE unitId = {$id}
	";
    $unit = mysqli_fetch_assoc($conn->query($sql));
    if ($unit['playerId'] != $_GET['playerid']) {
        die();
    }
    $columnDistance = abs($newCol - $unit['col']);
    $rowDistance = abs($newRow - $unit['row']);
    $tileDistance = $columnDistance + $rowDistance;
    if ($tileDistance <= $unit['movementPoints']) {
        $sql = "
        UPDATE Units
        SET 
        row = {$newRow},
        col = ($newCol) 
        WHERE unitId = {$id}
        ";
        $conn->query($sql);
        echo json_encode(array('col' => $newCol, 'row' => $newRow));
        die();
    }
    $conn->close();
    echo json_encode(array('col' => $unit['col'], 'row' => $unit['row']));
    die();
}
if (isset($_GET['updatemovement'])) {
    $servername = "localhost";
    $username = "johntheadmin";
    $password = "Echo120499!";
    $dbname = "civilizationInformation";
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "
        SELECT * FROM Units
        ";
    $units = [];
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $units[] = $row;
    }
    $unitsToGo = array();
    foreach ($units as $unit) {
        $unitsToGo[] = array('id' => $unit['unitId'], 'attack' => $unit['attack'], 'defense' => $unit['defense'], 'movementPoints' => $unit['movementPoints'], 'health' => $unit['health'], 'name' => $unit['name'], 'image' => $unit['image'], 'posTile' => array('row' => $unit['row'], 'col' => $unit['col']));
    }
    $conn->close();
    echo(json_encode($unitsToGo));
    die();
}
if (isset($_GET['attack'])) {
    $attackerId = $_GET['attackUnitId'];
    $defenderId = $_GET['defendUnitId'];
    $servername = "localhost";
    $username = "johntheadmin";
    $password = "Echo120499!";
    $dbname = "civilizationInformation";
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "
    SELECT * FROM Units
    WHERE
    unitId = {$attackerId}
    ";
    $attacker = mysqli_fetch_assoc($conn->query($sql));
    $sql = "
    SELECT * FROM Units
    WHERE
    unitId = {$defenderId}
    ";
    $defender = mysqli_fetch_assoc($conn->query($sql));
    if ($attacker['playerId'] != $_GET['playerid'] || $attacker['playerId'] == $defender['playerId']) {
        die();
    }
    $attackerRow = $attacker['row'];
    $attackerCol = $attacker['col'];
    $defenderRow = $defender['row'];
    $defenderCol = $defender['col'];
    $columnDistance = abs($defenderCol - $attackerCol);
    $rowDistance = abs($defenderRow - $attackerRow);
    $tileDistance = $columnDistance + $rowDistance;
    if ($tileDistance <= $attacker['movementPoints']) {
        $defender['health'] -= $attacker['attack'];
        $attacker['health'] -= $defender['defense'];
        $sql = "
            UPDATE Units
            SET
            health = {$attacker['health']}
            WHERE unitId = {$attackerId}
            ";
        $conn->query($sql);
        $sql = "
            UPDATE Units
            SET
            health = {$defender['health']}
            WHERE unitId = {$defenderId}
            ";
        $conn->query($sql);
        if ($attacker['health'] <= 0) {
            $sql = "
            DELETE FROM Units
            WHERE unitId = {$attackerId}
            ";
            $conn->query($sql);
        }
        if ($defender['health'] <= 0) {
            $sql = "
            DELETE FROM Units
            WHERE unitId = {$defenderId}
            ";
            $conn->query($sql);
            $attacker['row'] = $defender['row'];
            $attacker['col'] = $defender['col'];
            $sql = "
            UPDATE Units
            SET
            row = {$attacker['row']},
            col = {$attacker['col']}
            WHERE unitId = {$attackerId}
            ";
            $conn->query($sql);
        }
        $sql = "
        SELECT * FROM Units
        ";
        $units = [];
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $units[] = $row;
        }
        $conn->close();
        $unitsToGo = array();
        foreach ($units as $unit) {
            $unitsToGo[] = array('playerId' => $unit['playerId'], 'id' => $unit['unitId'], 'attack' => $unit['attack'], 'defense' => $unit['defense'], 'movementPoints' => $unit['movementPoints'], 'health' => $unit['health'], 'name' => $unit['name'], 'image' => $unit['image'], 'posTile' => array('row' => $unit['row'], 'col' => $unit['col']));
        }
        echo(json_encode($unitsToGo));
        die();
    } else {
        $conn->close();
        die();
    }
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