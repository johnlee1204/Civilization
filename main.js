/*jshint maxerr: 1000 */
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


class Tile {
    constructor(x, y, size, image) {
        this.x = x;
        this.y = y;
        this.size = size;
        this.image = image;
    }

    draw() {
        //ctx.fillStyle = this.color;
        ctx.drawImage(this.image, this.x - xOffSet, this.y - yOffSet, this.size, this.size);
    }
}

class BoardOfTiles {
    constructor() {
        this.tiles = [];
        for (var i = 0; i < 50; i++) {
            this.tiles[i] = [];
            for (var j = 0; j < 100; j++) {
                this.tiles[i][j] = new Tile(j * tileSize, i * tileSize, tileSize, images.black);
            }
        }
    }

    draw() {
        for (let i = 0; i < this.tiles.length; i++) {
            for (let j = 0; j < this.tiles[i].length; j++) {
                this.tiles[i][j].draw();
            }
        }
    }

    isoMap() {
    	for(let i = 0;i<this.tiles.length;i++){
    		for(let j = 0; j<this.tiles[i].length;j++)
    		{
    			let tempX = this.tiles[i][j].x - this.tiles[i][j].y;  
    			this.tiles[i][j].y = (this.tiles[i][j].x + this.tiles[i][j].y)/2;
    			this.tiles[i][j].x = tempX;
    		}
    	}
    }

    randomize1() {
        var randomTile = Math.floor(Math.random() * 10) + 1;
        var randomSwap = Math.floor(Math.random() * 2);
        for (let i = 0; i < this.tiles.length; i += 5) {
            for (let j = 0; j < this.tiles.length; j += 5) {
                if (randomSwap == 1) {
                    randomTile = Math.floor(Math.random() * 20) + 1;
                }
                randomSwap = Math.floor(Math.random() * 2);
                for (let k = 0; k < 5; k++) {
                    for (let l = 0; l < 5; l++) {
                        if (randomTile < 10)
                            this.tiles[i + k][j + l].image = images.grass;
                        else if (randomTile < 18)
                            this.tiles[i + k][j + l].image = images.water;
                        else if (randomTile < 20)
                            this.tiles[i + k][j + l].image = images.rock;
                        else
                            this.tiles[i + k][j + l].image = images.sand;
                    }
                }
            }
        }
    }

    randomize2() {
        var randomTile = Math.floor(Math.random() * 10) + 1;
        for (let i = 0; i < this.tiles.length; i++) {
            for (let j = 0; j < this.tiles[i].length; j++) {
                randomTile = Math.floor(Math.random() * 20) + 1;
                if (randomTile < 9)
                    this.tiles[i][j].image = images.grass;
                else if (randomTile < 17)
                    this.tiles[i][j].image = images.water;
                else if (randomTile < 18)
                    this.tiles[i][j].image = images.rock;
                else
                    this.tiles[i][j].image = images.sand;
            }
        }
        this.smooth();
        this.smooth();
        this.smooth();
        this.smooth();
        let grassToForest = Math.floor(Math.random()*4);
      	for(let i = 0; i<this.tiles.length;i++){
      		for(let j = 0; j<this.tiles[i].length;j++){
      			if(grassToForest == 1&&this.tiles[i][j].image == images.grass){
      				this.tiles[i][j].image = images.forest;
      			}
      			grassToForest = Math.floor(Math.random()*4);
      		}
      	}
    }

    smooth() {
        var gc = 0,
            wc = 0,
            rc = 0,
            sc = 0;
        for (let i = 0; i < this.tiles.length; i++) {
            for (let j = 0; j < this.tiles[i].length; j++) {
                let possible = [i > 0 ? this.tiles[i - 1][j] : -1, this.tiles[i][j - 1], this.tiles[i][j + 1], i < this.tiles.length - 1 ? this.tiles[i + 1][j] : -1];
                //alert('in possible');
                for (let k = 0; k < possible.length; k++) {
                    if (possible[k] == -1 || !(possible[k] || possible[k] === 0)) {
                        possible.splice(k, 1);
                        k--;
                    }
                }
                for (let k = 0; k < possible.length; k++) {
                    if (possible[k].image == images.grass)
                        gc++;
                    else if (possible[k].image == images.water)
                        wc++;
                    else if (possible[k].image == images.sand)
                        sc++;
                    else if (possible[k].image == images.rock)
                        rc++;
                }
                if (Math.max(gc, wc, sc, rc) == gc && Math.max(wc, sc, rc) < gc)
                    this.tiles[i][j].image = images.grass;
                else if (Math.max(gc, wc, sc, rc) == wc && Math.max(gc, sc, rc) < wc)
                    this.tiles[i][j].image = images.water;
                else if (Math.max(gc, wc, sc, rc) == sc && Math.max(wc, gc, rc) < sc)
                    this.tiles[i][j].image = images.sand;
                else if (Math.max(gc, wc, sc, rc) == rc && Math.max(wc, sc, gc) < rc)
                    this.tiles[i][j].image = images.rock;
                gc = 0;
                wc = 0;
                rc = 0;
                sc = 0;
            }
        }


        for (let i = this.tiles.length - 1; i >= 0; i--) {
            for (let j = this.tiles[i].length - 1; j >= 0; j--) {

                let possible = [i > 0 ? this.tiles[i - 1][j] : -1, this.tiles[i][j - 1], this.tiles[i][j + 1], i < this.tiles.length - 1 ? this.tiles[i + 1][j] : -1];
                //alert('in possible');
                for (let k = 0; k < possible.length; k++) {
                    if (possible[k] == -1 || !(possible[k] || possible[k] === 0)) {
                        possible.splice(k, 1);
                        k--;
                    }
                }
                for (let k = 0; k < possible.length; k++) {
                    if (possible[k].image == images.grass)
                        gc++;
                    else if (possible[k].image == images.water)
                        wc++;
                    else if (possible[k].image == images.sand)
                        sc++;
                    else if (possible[k].image == images.rock)
                        rc++;
                }
                if (Math.max(gc, wc, sc, rc) == gc && Math.max(wc, sc, rc) < gc)
                    this.tiles[i][j].image = images.grass;
                else if (Math.max(gc, wc, sc, rc) == wc && Math.max(gc, sc, rc) < wc)
                    this.tiles[i][j].image = images.water;
                else if (Math.max(gc, wc, sc, rc) == sc && Math.max(wc, gc, rc) < sc)
                    this.tiles[i][j].image = images.sand;
                else if (Math.max(gc, wc, sc, rc) == rc && Math.max(wc, sc, gc) < rc)
                    this.tiles[i][j].image = images.rock;
                gc = 0;
                wc = 0;
                rc = 0;
                sc = 0;
            }
        }




    }


    paintTile(x, y) {
        for (let i = 0; i < this.tiles.length; i++) {
            for (let j = 0; j < this.tiles[i].length; j++) {
                if (x > this.tiles[i][j].x && x < this.tiles[i][j].x + this.tiles[i][j].size && y > this.tiles[i][j].y && y < this.tiles[i][j].y + this.tiles[i][j].size) {
                    if (currentImage === 0)
                        this.tiles[i][j].image = images.black;
                    else if (currentImage == 1)
                        this.tiles[i][j].image = images.grass;
                    else if (currentImage == 2)
                        this.tiles[i][j].image = images.rock;
                    else if (currentImage == 3)
                        this.tiles[i][j].image = images.water;
                    else if (currentImage == 4)
                        this.tiles[i][j].image = images.sand;
                }
            }
        }
    }

    zoomStart() {
        //alert('zoomstart called');
        currentNumsMap = [];
        for (let i = 0; i < this.tiles.length; i++) {
            currentNumsMap[i] = [];
            for (let j = 0; j < this.tiles[i].length; j++) {
                if (this.tiles[i][j].image == images.black) {
                    currentNumsMap[i][j] = 0;
                } else if (this.tiles[i][j].image == images.grass) {
                    currentNumsMap[i][j] = 1;
                } else if (this.tiles[i][j].image == images.rock) {
                    currentNumsMap[i][j] = 2;
                } else if (this.tiles[i][j].image == images.water) {
                    currentNumsMap[i][j] = 3;
                } else if (this.tiles[i][j].image == images.sand) {
                    currentNumsMap[i][j] = 4;
                } else if (this.tiles[i][j].image == images.city) {
                    currentNumsMap[i][j] = 5;
                } else if (this.tiles[i][j].image == images.deepWater) {
                    currentNumsMap[i][j] = 6;
                }
                else if (this.tiles[i][j].image == images.forest) {
                    currentNumsMap[i][j] = 7;
                }
            }
        }
    }

    zoomEnd() {
        // alert('zoomend called');
        for (let i = 0; i < currentNumsMap.length; i++) {
            for (let j = 0; j < currentNumsMap[i].length; j++) {
                if (currentNumsMap[i][j] === 0) {
                    this.tiles[i][j].image = images.black;
                } else if (currentNumsMap[i][j] == 1) {
                    this.tiles[i][j].image = images.grass;
                } else if (currentNumsMap[i][j] == 2) {
                    this.tiles[i][j].image = images.rock;
                } else if (currentNumsMap[i][j] == 3) {
                    this.tiles[i][j].image = images.water;
                } else if (currentNumsMap[i][j] == 4) {
                    this.tiles[i][j].image = images.sand;
                } else if (currentNumsMap[i][j] == 5) {
                    this.tiles[i][j].image = images.city;
                } else if (currentNumsMap[i][j] == 6) {
                    this.tiles[i][j].image = images.deepWater;
                }
                else if (currentNumsMap[i][j] == 7) {
                    this.tiles[i][j].image = images.forest;
                }
            }
        }
    }

    save() {
        this.numsMap = [];
        for (let i = 0; i < this.tiles.length; i++) {
            this.numsMap[i] = [];
            for (let j = 0; j < this.tiles[i].length; j++) {
                if (this.tiles[i][j].image == images.black) {
                    this.numsMap[i][j] = 0;
                } else if (this.tiles[i][j].image == images.grass) {
                    this.numsMap[i][j] = 1;
                } else if (this.tiles[i][j].image == images.rock) {
                    this.numsMap[i][j] = 2;
                } else if (this.tiles[i][j].image == images.water) {
                    this.numsMap[i][j] = 3;
                } else if (this.tiles[i][j].image == images.sand) {
                    this.numsMap[i][j] = 4;
                } else if (this.tiles[i][j].image == images.city) {
                    this.numsMap[i][j] = 5;
                } else if (this.tiles[i][j].image == images.deepWater) {
                    this.numsMap[i][j] = 6;
                }
                else if (this.tiles[i][j].image == images.forest) {
                    this.numsMap[i][j] = 7;
                }
            }
        }
        let name = prompt('Please Name the Save');
        if (name !== '') {
            //window.open('save.php?name=' + name + '&map=' + this.numsMap + '&width=' + this.tiles.length + '&height=' + this.tiles[0].length, 'Map Save');
            //alert('save called');
            var oReqs = new XMLHttpRequest();
            oReqs.onload = function() {

            };
            oReqs.open("get", 'php/save.php?name=' + name + '&map=' + this.numsMap + '&width=' + this.tiles.length + '&height=' + this.tiles[0].length, true);
            oReqs.send();
        } else {
            alert('You did not enter a name');
        }
    }

    load() {
        var mapToLoadAsString;
        var scope = this;
        currentFile = prompt('What would you like to name the file to open?');
        var oReq = new XMLHttpRequest(); //New request object
        oReq.onload = function() {
            mapToLoadAsString = oReq.responseText;
            scope.mapToLoad = mapToLoadAsString.split(',');
            //console.log(scope.mapToLoad);
            //alert(mapToLoadAsString);
            let k = 2;
            for (let i = 0; i < scope.mapToLoad[0]; i++) {
                for (let j = 0; j < scope.mapToLoad[1]; j++) {
                    if (scope.mapToLoad[k] == 1)
                        scope.tiles[i][j].image = images.grass;
                    else if (scope.mapToLoad[k] == 2)
                        scope.tiles[i][j].image = images.rock;
                    else if (scope.mapToLoad[k] == 3)
                        scope.tiles[i][j].image = images.water;
                    else if (scope.mapToLoad[k] == 4)
                        scope.tiles[i][j].image = images.sand;
                    else if (scope.mapToLoad[k] == 5)
                        scope.tiles[i][j].image = images.city;
                    else if (scope.mapToLoad[k] == 6)
                        scope.tiles[i][j].image = images.deepWater;
                    else if (scope.mapToLoad[k] == 7)
                        scope.tiles[i][j].image = images.forest;
                    k++;
                }
            }
        };
        oReq.open("get", "php/load.php?name=" + currentFile, true);
        oReq.send();
        tileSize = 32;
    }

    serverRandomLoad() {
        var mapToLoadAsString;
        var scope = this;
        var oReq = new XMLHttpRequest(); //New request object
        oReq.onload = function() {
            mapToLoadAsString = oReq.responseText;
            scope.mapToLoad = mapToLoadAsString.split(',');
            //console.log(scope.mapToLoad);
            //alert(mapToLoadAsString);
            let k = 2;
            for (let i = 0; i < scope.mapToLoad[0]; i++) {
                for (let j = 0; j < scope.mapToLoad[1]; j++) {
                    if (scope.mapToLoad[k] == 1)
                        scope.tiles[i][j].image = images.grass;
                    else if (scope.mapToLoad[k] == 2)
                        scope.tiles[i][j].image = images.rock;
                    else if (scope.mapToLoad[k] == 3)
                        scope.tiles[i][j].image = images.water;
                    else if (scope.mapToLoad[k] == 4)
                        scope.tiles[i][j].image = images.sand;
                    else if (scope.mapToLoad[k] == 5)
                        scope.tiles[i][j].image = images.city;
                    else if (scope.mapToLoad[k] == 6)
                        scope.tiles[i][j].image = images.deepWater;
                    else if (scope.mapToLoad[k] == 7)
                        scope.tiles[i][j].image = images.forest;
                    k++;
                }
            }
        };
        oReq.open("get", "php/serverMapCreate.php", true);
        oReq.send();
        tileSize = 32;
    }
}

function painting() {
    if (press) {
        board.paintTile(mouseX - canvas.getBoundingClientRect().left + xOffSet, mouseY - canvas.getBoundingClientRect().top + yOffSet);
    }

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

var board = new BoardOfTiles();

function update() {
    ctx.beginPath();
    ctx.fillStyle = 'black';
    changeView();
    painting();
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    board.draw();
    //ctx.fillRect(0, 0, canvas.width, canvas.height);
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
            board.load();
            break;
        case 82: //r
            board.randomize1();
            break;
        case 84: //t
            board.smooth();
            break;
        case 85: //u
            board.randomize2();
            break;
        case 86: //v
            board.serverRandomLoad();
            break;
        case 87: //w
            console.log(board.tiles[10][10].x);
            board.isoMap();
                console.log(board.tiles[10][10].x);
            break;
        case 77: //m
            xOffSet = 0;
            yOffSet = 0;
            break;
        case 83: //s key
            board.save();
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
                board.zoomStart();
                board = new BoardOfTiles();
                board.zoomEnd();
            }
            break;
        case 88: //x
            if (tileSize < 128) {
                tileSize *= 1.1;
                board.zoomStart();
                board = new BoardOfTiles();
                board.zoomEnd();
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
            board.zoomStart();
            board = new BoardOfTiles();
            board.zoomEnd();
        }
    } else {
        if (tileSize < 128) {
            tileSize *= 1.1;
            board.zoomStart();
            board = new BoardOfTiles();
            board.zoomEnd();
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