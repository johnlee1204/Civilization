<<<<<<< HEAD
/*jshint maxerr: 1000 */
var canvas = document.createElement('canvas');
document.body.appendChild(canvas);
canvas.oncontextmenu = function(e) {
    e.preventDefault();
};
canvas.style.backgroundColor = "black";
canvas.width = 1024;
canvas.height = 576;
canvas.style.display = 'block';
canvas.style.marginLeft = 'auto';
canvas.style.marginRight = 'auto';
var ctx = canvas.getContext('2d');
var currentImage = 0;
var blackTile = document.createElement('img');
blackTile.src = 'images/blackTile.png';
blackTile.style.display = 'none';
document.body.appendChild(blackTile);
var grassTile = document.createElement('img');
grassTile.src = 'images/grassTile.jpeg';
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
    deepWater: deepWaterTile
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
        //this.mapSize = 1600*tileSize/tileSize;
        for (var i = 0; i < 50; i++) {
            this.tiles[i] = [];
            for (var j = 0; j < 50; j++) {
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
            }
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
                    k++;
                }
            }
        };
        oReq.open("get", "php/load.php?name=" + currentFile, true);
        oReq.send();
        tileSize = 32;
    }

function changeView() {
    if (up) yOffSet -= 10;
    if (down) yOffSet += 10;
    if (right) xOffSet += 10;
    if (left) xOffSet -= 10;
}

var board = new BoardOfTiles();

function update() {
    ctx.beginPath();
    ctx.fillStyle = 'black';
    changeView();
    painting();
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    board.draw();
    ctx.fill();
    ctx.closePath();
}
document.addEventListener("keydown", function(event) {
    switch (event.keyCode) {
             
        case 77: //m
            xOffSet = 0;
            yOffSet = 0;
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
            if (tileSize > 10) {
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
    
});
canvas.addEventListener("mousewheel", function(event) {
    //alert(board.tiles[0][0].x+','+board.tiles[0][0].y);
    if (event.deltaY > 0) {
        if (tileSize > 10) {
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

=======
/*jshint maxerr: 1000 */
var canvas = document.createElement('canvas');
document.body.appendChild(canvas);
canvas.oncontextmenu = function(e) {
    e.preventDefault();
};
canvas.style.backgroundColor = "black";
canvas.width = 1024;
canvas.height = 576;
canvas.style.display = 'block';
canvas.style.marginLeft = 'auto';
canvas.style.marginRight = 'auto';
var ctx = canvas.getContext('2d');
var currentImage = 0;
var blackTile = document.createElement('img');
blackTile.src = 'images/blackTile.png';
blackTile.style.display = 'none';
document.body.appendChild(blackTile);
var grassTile = document.createElement('img');
grassTile.src = 'images/grassTile.jpeg';
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
    deepWater: deepWaterTile
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
        //this.mapSize = 1600*tileSize/tileSize;
        for (var i = 0; i < 50; i++) {
            this.tiles[i] = [];
            for (var j = 0; j < 50; j++) {
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
            }
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
                    k++;
                }
            }
        };
        oReq.open("get", "php/load.php?name=" + currentFile, true);
        oReq.send();
        tileSize = 32;
    }

function changeView() {
    if (up) yOffSet -= 10;
    if (down) yOffSet += 10;
    if (right) xOffSet += 10;
    if (left) xOffSet -= 10;
}

var board = new BoardOfTiles();

function update() {
    ctx.beginPath();
    ctx.fillStyle = 'black';
    changeView();
    painting();
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    board.draw();
    ctx.fill();
    ctx.closePath();
}
document.addEventListener("keydown", function(event) {
    switch (event.keyCode) {
             
        case 77: //m
            xOffSet = 0;
            yOffSet = 0;
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
            if (tileSize > 10) {
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
    
});
canvas.addEventListener("mousewheel", function(event) {
    //alert(board.tiles[0][0].x+','+board.tiles[0][0].y);
    if (event.deltaY > 0) {
        if (tileSize > 10) {
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

>>>>>>> 202ef54839033042ef5ca7abbb7fe11b4d9c5583
setInterval(update, 10);