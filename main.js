/*jshint maxerr: 1000 */

document.getElementById('buttonS').onclick = beginGame;
var canvas = document.createElement('canvas');
document.body.appendChild(canvas);
canvas.oncontextmenu = function(e) {
    e.preventDefault();
};
canvas.style.backgroundColor = "black";
canvas.width = window.screen.availWidth;
canvas.height = window.screen.availHeight+40;
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
var tileSize = 32;

var currentNumsMap;

document.body.appendChild(waterTile);
var images = {
    black: blackTile,
    grass: grassTile,
    rock: rockTile,
    water: waterTile,
    sand: sandTile,
    city: cityTile,
    deepWater: deepWaterTile,
    forest:forestTile
};
var imagesAsNum = [blackTile, grassTile, rockTile, waterTile, sandTile, cityTile, deepWaterTile,forestTile];
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
let board;




function beginGame() {
    var mapToLoadAsString;
    var scope = this;
    var oReq = new XMLHttpRequest(); //New request object
    oReq.onload = function() {
        //mapToLoadAsString = oReq.response;
        //scope.mapToLoad = mapToLoadAsString.split(',');
        let serverReply = JSON.parse(oReq.response);
       	board = serverReply.gameboard;
       	//let player1 = serverReply.player1.unserialize();
       	drawing = true;
    };
    oReq.open("get", "php/GameRunner.php", true);
    oReq.send();
    tileSize = 32;
    document.getElementById('startButton').style.display = "none";
    document.getElementById('buttonS').style.display = "none";
}

function drawBoard(board){
    for (let i = 0; i < board.tiles.length; i++) {
        for (let j = 0; j < board.tiles[i].length; j++) {
        	if(imagesAsNum[board.tiles[i][j].image]){
       		     ctx.drawImage(imagesAsNum[board.tiles[i][j].image],j*tileSize-xOffSet,i*tileSize-yOffSet,tileSize,tileSize);
       		}
        }
    }
}


function endTurn(){

}

function changeView() {
    if (up&&board.tiles[0][0].y+yOffSet>-9)
    {
    	yOffSet -= 10;
    }
    if (down&&board.tiles[board.tiles.length-1][0].y-yOffSet>canvas.height-tileSize-9)
    { 
    	yOffSet += 10;
    }
    if (right&&board.tiles[0][board.tiles[0].length-1].x-xOffSet>canvas.width-tileSize-9)
    { 
    	xOffSet += 10;
    }
    if (left&&board.tiles[0][0].x+xOffSet>9)
    { 

    	xOffSet -= 10;
    }
}

function update() {
    ctx.beginPath();
    ctx.fillStyle = 'black';
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    //ctx.fillRect(0, 0, canvas.width, canvas.height);
    if(drawing){
    	changeView();
    	drawBoard(board);
    }
    ctx.fill();
    ctx.closePath();
}
document.addEventListener("keydown", function(event) {
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
            break;
        case 82: //r
            break;
        case 84: //t
            break;
        case 85: //u
            break;
        case 86: //v
            beginGame();
            break;
        case 87: //w
            break;
        case 77: //m
            xOffSet = 0;
            yOffSet = 0;
            break;
        case 83: //s key
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
            if (tileSize*board.tiles[0].length >= window.screen.availWidth) {
                tileSize /= 1.1;
            }
            break;
        case 88: //x
            if (tileSize < 128) {
                tileSize *= 1.1;
            }
            break;

        default:

            break;
    }
});
canvas.addEventListener("mousewheel", function(event) {
    //alert(board.tiles[0][0].x+','+board.tiles[0][0].y);
    if (event.deltaY > 0) {
        if (tileSize*board.tiles[0].length >= window.screen.availWidth) {
            tileSize /= 1.1;
        }
    } else {
        if (tileSize < 128) {
            tileSize *= 1.1;
        }
    }
});


document.addEventListener("keyup", function(event) {
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
canvas.addEventListener("mousedown", function(event) {
    press = true;
});
canvas.addEventListener("mouseup", function(event) {
    press = false;
});
canvas.addEventListener("mousemove", function(event) {
    mouseX = event.clientX;
    mouseY = event.clientY;
});

setInterval(update, 10);