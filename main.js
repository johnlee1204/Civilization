/*jshint maxerr: 1000 */

document.getElementById('buttonS').onclick = beginGame;
var canvas = document.createElement('canvas');
document.body.appendChild(canvas);
canvas.oncontextmenu = function (e) {
    e.preventDefault();
};
canvas.style.backgroundColor = "black";
canvas.width = window.screen.availWidth;
canvas.height = window.screen.availHeight + 40;
canvas.style.display = 'block';
var ctx = canvas.getContext('2d');
var currentImage = 0;
var blackTile = document.createElement('img');
blackTile.src = 'images/blackTile.png';
blackTile.style.display = 'none';
document.body.appendChild(blackTile);
var grassTile = document.createElement('img');
grassTile.src = 'images/grassTile.png';
grassTile.style.display = 'none';
document.body.appendChild(grassTile);
var rockTile = document.createElement('img');
rockTile.src = 'images/rockTile.jpg';
rockTile.style.display = 'none';
document.body.appendChild(rockTile);
var waterTile = document.createElement('img');
waterTile.src = 'images/waterTile.png';
waterTile.style.display = 'none';
var sandTile = document.createElement('img');
document.body.appendChild(sandTile);
sandTile.src = 'images/sandTile.jpg';
sandTile.style.display = 'none';
var cityTile = document.createElement('img');
document.body.appendChild(cityTile);
cityTile.src = 'images/cityTile.jpg';
cityTile.style.display = 'none';
var deepWaterTile = document.createElement('img');
document.body.appendChild(deepWaterTile);
deepWaterTile.src = 'images/deepWaterTile.png';
deepWaterTile.style.display = 'none';
var forestTile = document.createElement('img');
document.body.appendChild(forestTile);
forestTile.src = 'images/forestTile.png';
forestTile.style.display = 'none';
var warrior = document.createElement("img");
document.body.appendChild(warrior);
warrior.src = 'images/warrior.png';
warrior.style.display = 'none';
var settler = document.createElement("img");
document.body.appendChild(settler);
settler.src = 'images/settler.png';
settler.style.display = 'none';
var scroll = document.createElement("img");
document.body.appendChild(scroll);
scroll.src = 'images/scroll.jpg';
scroll.style.display = 'none';

document.body.appendChild(waterTile);
var images = {
    black: blackTile,
    grass: grassTile,
    rock: rockTile,
    water: waterTile,
    sand: sandTile,
    city: cityTile,
    deepWater: deepWaterTile,
    forest: forestTile,
    scroll: scroll
};
var imagesAsNum = [blackTile, grassTile, rockTile, waterTile, sandTile, cityTile, deepWaterTile, forestTile];
var unitImagesAsNum = [warrior, settler];

var xOffSet = 0;
var yOffSet = 0;
var up = true;
var right = true;
var down = true;
var left = true;
var press = false;
var mouseX;
var mouseY;
var currentFile;
let drawing = false;
var tileSize = 32;
let board;
let player1;
var units = [];
let unitSelected;
let possibleMoves = [];
let started = false;
let moving = false;
let updatingMovement = false;
let warning = false;
var cities = [];

const playerId = prompt("Which Player are you?");

function reset(){
	var scope = this;
    var oReq = new XMLHttpRequest(); //New request object
    oReq.onload = function () {
    	location.reload();
    };
    oReq.open("get", "php/GameRunner.php?resetgame=true", true);
    oReq.send();
}


function beginGame() {
    var mapToLoadAsString;
    var scope = this;
    var oReq = new XMLHttpRequest(); //New request object
    oReq.onload = function () {
        //mapToLoadAsString = oReq.response;
        //scope.mapToLoad = mapToLoadAsString.split(',');
        units = [];
        let serverReply = JSON.parse(oReq.response);
        board = serverReply.gameBoard;
        player1 = serverReply.player1;
        player2 = serverReply.player2;
        units = serverReply.units;
        /*for(let i = 0;i<player1.units.length;i++){
            units.push(player1.units[i]);
        }*/
        drawing = true;
        update();
        updatingMovement = false;
    };
    oReq.open("get", "php/GameRunner.php?start=true&nextturn=true", true);
    oReq.send();
    tileSize = 32;
    document.getElementById('startButton').style.display = "none";
    document.getElementById('buttonS').style.display = "none";
}

function drawBoard() {
    for (let i = 0; i < board.tiles.length; i++) {
        for (let j = 0; j < board.tiles[i].length; j++) {
            if (imagesAsNum[board.tiles[i][j].image]) {
                if (possibleMoves.includes(board.tiles[i][j])) {
                	ctx.globalAlpha = .5;
                    ctx.drawImage(imagesAsNum[0], j * tileSize - xOffSet, i * tileSize - yOffSet, tileSize, tileSize);
                    ctx.globalAlpha = 1;
                }
                else {
                    ctx.drawImage(imagesAsNum[board.tiles[i][j].image], j * tileSize - xOffSet, i * tileSize - yOffSet, tileSize, tileSize);
                }
            }
        }
    }
    for (let i = 0; i < units.length; i++) {
        ctx.drawImage(unitImagesAsNum[units[i].image], units[i].posTile.col * tileSize - xOffSet + .25 * tileSize, units[i].posTile.row * tileSize - yOffSet + .25 * tileSize, tileSize / 2, tileSize / 1.5);
    }

    if(unitSelected){
    	ctx.fillStyle = "black";
    	ctx.drawImage(images.scroll,0,canvas.height*.7,canvas.width*.2,canvas.height*.3);
    	ctx.fillStyle = "black";
    	ctx.font = "20px Georgia"
    	ctx.fillText("Unit: "+unitSelected.name.replace('\\',''),40,canvas.height*.7+50);
    	ctx.fillText("Health: "+unitSelected.health,40,canvas.height*.7+120);
    	ctx.fillText("Attack: "+unitSelected.attack,40,canvas.height*.7+190);
    	ctx.fillText("Defense: "+unitSelected.defense,40,canvas.height*.7+260);
    }

    if (unitSelected && possibleMoves.length == 0 && unitSelected.playerId == playerId) {
        let mp = unitSelected.movementPoints;
        let pos = unitSelected.posTile;

        for (let i = 1; i <= mp; i++) {
        	if(parseInt(pos.row) - i>=0){
            	possibleMoves.push(board.tiles[parseInt(pos.row) - i][parseInt(pos.col)].image != "3" && board.tiles[parseInt(pos.row) - i][parseInt(pos.col)].image != "6" ? board.tiles[parseInt(pos.row) - i][parseInt(pos.col)] : null);
        	}
        	if(parseInt(pos.row) + i < board.tiles.length){
            	possibleMoves.push(board.tiles[parseInt(pos.row) + i][parseInt(pos.col)].image != "3" && board.tiles[parseInt(pos.row) + i][parseInt(pos.col)].image != "6" ? board.tiles[parseInt(pos.row) + i][parseInt(pos.col)] : null);
            }
            if(parseInt(pos.col) - i>=0){
            	possibleMoves.push(board.tiles[parseInt(pos.row)][parseInt(pos.col) - i].image != "3" && board.tiles[parseInt(pos.row)][parseInt(pos.col) - i].image != "6" ? board.tiles[parseInt(pos.row)][parseInt(pos.col) - i] : null);
            }
            if(parseInt(pos.col) + i<board.tiles[0].length){
            	possibleMoves.push(board.tiles[parseInt(pos.row)][parseInt(pos.col) + i].image != "3" && board.tiles[parseInt(pos.row)][parseInt(pos.col) + i].image != "6" ? board.tiles[parseInt(pos.row)][parseInt(pos.col) + i] : null);
            }
            if (i !== 0 && i % 2 == 0) {
            	if(parseInt(pos.row) - i / 2>=0&&parseInt(pos.col) - i / 2>=0){
                	possibleMoves.push(board.tiles[parseInt(pos.row) - i / 2][parseInt(pos.col) - i / 2].image != "3" && board.tiles[parseInt(pos.row) - i / 2][parseInt(pos.col) - i / 2].image != "6" ? board.tiles[parseInt(pos.row) - i / 2][parseInt(pos.col) - i / 2] : null);
                }
                if(parseInt(pos.row) + i / 2<board.tiles.length&&parseInt(pos.col) + i / 2<board.tiles[0].length){
                	possibleMoves.push(board.tiles[parseInt(pos.row) + i / 2][parseInt(pos.col) + i / 2].image != "3" && board.tiles[parseInt(pos.row) + i / 2][parseInt(pos.col) + i / 2].image != "6" ? board.tiles[parseInt(pos.row) + i / 2][parseInt(pos.col) + i / 2] : null);
                }
                if(parseInt(pos.row) - i / 2>=0&&parseInt(pos.col) + i / 2<board.tiles[0].length){
                	possibleMoves.push(board.tiles[parseInt(pos.row) - i / 2][parseInt(pos.col) + i / 2].image != "3" && board.tiles[parseInt(pos.row) - i / 2][parseInt(pos.col) + i / 2].image != "6" ? board.tiles[parseInt(pos.row) - i / 2][parseInt(pos.col) + i / 2] : null);
                }
                if(parseInt(pos.row) + i / 2<board.tiles.length&&parseInt(pos.col) - i / 2>=0){
                	possibleMoves.push(board.tiles[parseInt(pos.row) + i / 2][parseInt(pos.col) - i / 2].image != "3" && board.tiles[parseInt(pos.row) + i / 2][parseInt(pos.col) - i / 2].image != "6" ? board.tiles[parseInt(pos.row) + i / 2][parseInt(pos.col) - i / 2] : null);
                }
            }
        }
        update();
    }
    else if (!unitSelected) {
        possibleMoves = [];
	}
	if(warning){
	        ctx.fillStyle = "black";
	    	ctx.drawImage(images.scroll,canvas.width/5*2,0,canvas.width/5,canvas.height*.1);
	    	ctx.fillStyle = "black";
	    	ctx.font = "20px Georgia"
	    	ctx.fillText("You can't control that player",canvas.width/5*2+10,20);
		}
}


function endTurn() {
    if (drawing) {
        var scope = this;
        var oReq = new XMLHttpRequest(); //New request object
        oReq.onload = function () {
            if (oReq.response && isJson(oReq.response)) {
                let serverReply = JSON.parse(oReq.response);
                if (serverReply.gameBoard && serverReply.player1) {
                    units = [];
                    board = serverReply.gameBoard;
                    player1 = serverReply.player1;
                    player2 = serverReply.player2;
                    units = serverReply.units;
                    drawing = true;
                    update();
                }
            }
        };
        oReq.open("get", "php/GameRunner.php?nextturn=true&update=true", true);
        oReq.send();
    }
}

function changeView() {
    if (drawing) {
        let tempX = xOffSet;
        let tempY = yOffSet;
        if (up && board.tiles[0][0].y + yOffSet > -9) {
            yOffSet -= 20;
        }
        if (down && board.tiles.length * tileSize - yOffSet > canvas.height - 9) {
            yOffSet += 20;
        }
        if (right && board.tiles[0].length * tileSize - xOffSet > canvas.width - 9) {
            xOffSet += 20;
        }
        if (left && board.tiles[0][0].x + xOffSet > 9) {

            xOffSet -= 20;
        }
        if (tempX != xOffSet || tempY != yOffSet) {
            update();
        }
    }
}

function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}



function moveUnit(unit, newX, newY) {
    let newRow = Math.floor(newY / tileSize);
    let newCol = Math.floor(newX / tileSize);
    if(!moving&&board.tiles[newRow][newCol].image!="3"&&board.tiles[newRow][newCol].image!="6" && unit.playerId == playerId) {
        moving = true;
        let newRow = Math.floor(newY / tileSize);
        let newCol = Math.floor(newX / tileSize);
        let unitId = unit.id;
        update();
        //check for attack
        let attacking = false;
        let defendingId = null;
        for(let i = 0; i< units.length;i++){
        	if(units[i].posTile.row == newRow && units[i].posTile.col == newCol){
        		attacking = true;
        		defendingId = units[i].id;
        	}
        }
        //attacking
        if(attacking&&unitId!=defendingId){
        	var scope = this;
	        var oReq = new XMLHttpRequest(); //New request object
	        oReq.onload = function () {
	            if (oReq.response && isJson(oReq.response)) {
                    let serverReply = JSON.parse(oReq.response);
                    unitsFromServer = serverReply;
                    units = unitsFromServer;
                    update();
                }
	            moving = false;
	        };
	        oReq.open("get", "php/GameRunner.php?attack=true&attackUnitId=" + unitId + "&defendUnitId=" + defendingId+"&playerid="+playerId, true);
	        oReq.send();
        }
        //not attacking
        else{
	        var scope = this;
	        var oReq = new XMLHttpRequest(); //New request object
	        oReq.onload = function () {
	            if (oReq.response && isJson(oReq.response)) {
	                let serverReply = JSON.parse(oReq.response);
	                newTile = serverReply;
	                unit.posTile.row = newTile.row;
	                unit.posTile.col = newTile.col;
	                update();
	            }
	            moving = false;
	        };
	        oReq.open("get", "php/GameRunner.php?move=true&unitId=" + unitId + "&newRow=" + newRow + "&newCol=" + newCol + "&update=true&playerid="+playerId, true);
	        oReq.send();
    	}
    }
    else if(moving){
        alert("Please wait for the last movement to finish");
    }
    else if(unit.playerId != playerId)
    {
    	var count = 0;
    	var warningInterval = setInterval(function(){
    		warning = true;
    		count++;
	    	if(count>200){
	    		warning = false;
	    		clearInterval(warningInterval);
	    	}
    	},10);
    	update();
	}
}

function foundCity(unit){
	let name = prompt("City Name?");
	var scope = this;
            var oReq = new XMLHttpRequest(); //New request object
            oReq.onload = function () {
            	console.log('1');
                if (oReq.response && isJson(oReq.response)) {
                    let serverReply = JSON.parse(oReq.response);
                    console.log('2');
	                if (serverReply.gameBoard) {
	                    units = [];
	                    board = serverReply.gameBoard;
	                    units = serverReply.units;
	                    cities = serverReply.cities;
	                    console.log('all');
	                    console.log(units);
	                    drawing = true;
	                    update();
	                }
                }
            };
            oReq.open("get", "php/GameRunner.php?nextturn=true&foundcity=true&playerid="+unit.playerId+"&name="+name+"&settlerid="+unit.id, true);
            oReq.send();
}


function updateMovement() {
    if(!updatingMovement&&!moving) {
        updatingMovement = true;
        if (drawing) {
            var scope = this;
            var oReq = new XMLHttpRequest(); //New request object
            oReq.onload = function () {
                if (oReq.response && isJson(oReq.response)&&!moving) {
                    let serverReply = JSON.parse(oReq.response);
                    unitsFromServer = serverReply;
                    for (let i = 0; i < unitsFromServer.length; i++) {
                        if (units[i].id == unitsFromServer[i].id) {
                            units[i].posTile.row = unitsFromServer[i].posTile.row;
                            units[i].posTile.col = unitsFromServer[i].posTile.col;
                        }
                    }
                    update();
                }
                updatingMovement = false;
            };
            oReq.open("get", "php/GameRunner.php?updatemovement=true", true);
            oReq.send();
        }
    }
}

function checkIfSelectingUnit(x, y) {
    for (let i = 0; i < units.length; i++) {
        if (Math.floor(x / tileSize) == units[i].posTile.col && Math.floor(y / tileSize) == units[i].posTile.row) {
            unitSelected = units[i]
            update();
            return;
        }
    }
}

function update() {
    ctx.beginPath();
    ctx.fillStyle = 'black';
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    if (drawing) {
        drawBoard();
    }
    ctx.fill();
    ctx.closePath();
}

document.addEventListener("keydown", function (event) {
    switch (event.keyCode) {
        case 48: //zero
            currentImage = 0;
            alert('Black');
            break;
        case 49: //one
            currentImage = 1;
            alert('Grass');
            break;

        case 50: //two
            currentImage = 2;
            alert('Rock');
            break;
        case 51: //three
            currentImage = 3;
            alert('Water');
            break;
        case 52: //four
            currentImage = 4;
            alert("Sand");
            break;
        case 96: //zero num pad
            currentImage = 0;
            alert('Black');
            break;
        case 97: //one num pad
            currentImage = 1;
            alert('Grass');
            break;
        case 98: //two num pad
            currentImage = 2;
            alert('Rock');
            break;
        case 99: //three num pad
            currentImage = 3;
            alert('Water');
            break;
        case 100: //four
            currentImage = 4;
            alert("Sand");
            break;
        case 76: //l
            updateMovement();
            break;
        case 78://n
            endTurn();
            break;
        case 82: //r
        reset();
            break;
        case 84: //t
            break;
        case 85: //u
            break;
        case 86: //v
            break;
        case 87: //w
            break;
        case 77: //m
            xOffSet = 0;
            yOffSet = 0;
            break;
        case 83: //s key
        	if(unitSelected&&unitSelected.image == 1){
        		foundCity(unitSelected);
        		unitSelected = false;
        	}

            break;
        case 37: //left
            right = false;
            break;
        case 38: //up
            down = false;
            break;
        case 39: //right
            left = false;
            break;
        case 40: //down
            up = false;
            break;
        case 90: //z
            if (drawing && tileSize * board.tiles[0].length >= window.screen.availWidth) {
                tileSize /= 1.1;
                update();
            }
            break;
        case 88: //x
            if (drawing && tileSize < 128) {
                tileSize *= 1.1;
                update();
            }
            break;

        default:

            break;
    }
});
canvas.addEventListener("mousewheel", function (event) {
    if (drawing && event.deltaY > 0) {
        if (tileSize * board.tiles[0].length >= window.screen.availWidth) {
            tileSize /= 1.1;
            update();
        }
    } else {
        if (drawing && tileSize < 128) {
            tileSize *= 1.1;
            update();
        }
    }
});


document.addEventListener("keyup", function (event) {
    switch (event.keyCode) {
        case 37: //left
            right = true;
            break;
        case 38: //up
            down = true;
            break;
        case 39: //right
            left = true;
            break;
        case 40: //down
            up = true;
            break;

        default:

            break;
    }
});
canvas.addEventListener("mousedown", function (event) {
    if (drawing && !unitSelected) {
        checkIfSelectingUnit(event.clientX + xOffSet, event.clientY + yOffSet);
    }
    else if (drawing && unitSelected) {
        moveUnit(unitSelected, event.clientX + xOffSet, event.clientY + yOffSet);
        unitSelected = false;
    }
    update();
});
canvas.addEventListener("mouseup", function (event) {
    press = false;
});
canvas.addEventListener("mousemove", function (event) {
    mouseX = event.clientX;
    mouseY = event.clientY;
});

setInterval(changeView, 10);
setInterval(updateMovement, 500);