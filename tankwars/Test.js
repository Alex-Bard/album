var game ={
    sprites: {
        //background1: undefined,
        tank1: undefined,
        tank2: undefined,
        gun1: undefined,
        gun2: undefined,
        shot: undefined,
        boom:undefined,
    },
    points: [],
    Tank1startPosition:300,
    qualityMap:1,//1- лучшее
    Tank2startPosition:1000,
    move:1,
    numOfSegments:15,
    variations:0.1,
    boom:1,
    calcedPoints:[],
    map: [],
    lineMap:[],
    tank1Speed: 1,
    cross:50,
    width :1280,
    height: 720,
    war: false,
};
var TO_RADIANS = Math.PI/180;
game.load = function() {
    for (let key in this.sprites) {
        this.sprites[key] = new Image();
        this.sprites[key].src = 'images/' + key + '.png';
    }
}
game.gener = function(){
    this.ctx.clearRect(0,0,this.width,this.height);
    var numOfSegments = this.numOfSegments;                      // split horizontal space
    var segment = canvas.width / numOfSegments; // calc width of each segment
    var variations = this.variations;                      // adjust this: lower = less variations
    var i;
    game.ctx.beginPath();
    game.ctx.moveTo(canvas.width, canvas.height);
    game.ctx.lineTo(0, canvas.height);

    for(i=0;i<game.lineMap.length;i+=2) {this.ctx.lineTo(game.lineMap[i], game.lineMap[i+1]);}//see below
    game.ctx.closePath();
    game.ctx.fillStyle = 'green';
    game.ctx.fill();
    if (!game.tank1.dead) {
        game.drawRotatedImage(this.sprites.tank1, this.sprites.gun1, this.tank1.x, this.tank1.Y(this.tank1.x) - this.tank1.height, game.tank1.engle, game.gun1.engle * TO_RADIANS);
    }
    else{
        this.ctx.drawImage(this.sprites.tank1,  this.tank1.x,this.tank1.Y(this.tank1.x) - this.tank1.height);
    }
    if (!game.tank2.dead) {
        game.drawRotatedImage(this.sprites.tank2, this.sprites.gun2, this.tank2.x, this.tank2.Y(this.tank2.x) - this.tank2.height, game.tank2.engle, game.gun2.engle * TO_RADIANS);
    }
    else{
        this.ctx.drawImage(this.sprites.tank2,  this.tank2.x,this.tank2.Y(this.tank2.x) - this.tank2.height);
    }
    //game.drawEllipse(this.tank1.x+300,this.tank1.Y(this.tank1.x)-this.tank1.height,300,300,0);
    if (game.shot.isAlive) {
        this.ctx.drawImage(this.sprites.shot, this.shot.x + 20, this.shot.y - 37, 10, 10);
    }
    if (game.shot.boomm){
        this.ctx.drawImage(this.sprites.boom, 810 * game.shot.frame,0,810,810, game.shot.x-35, game.shot.y-35,100,100);
    }
    this.ctx.font = "30px Arial";
    this.ctx.fillStyle = "#000000";
    this.ctx.fillText("power: " + this.gun1.power, 15, 35);
    this.ctx.font = "30px Arial";
    this.ctx.fillStyle = "red";
    this.ctx.fillText("power: " + this.gun2.power, game.width-200, 35);
    };
game.over= function(message){
    alert(message);
    this.running = false;
    window.location.reload();
};
game.initMap = function() {
    var numOfSegments = game.numOfSegments;                      // split horizontal space
    var segment = canvas.width / numOfSegments; // calc width of each segment
    var variations = game.variations;                      // adjust this: lower = less variations
    var i;
    for (i = 0; i < numOfSegments + 1; i++) {
        game.points.push(segment * i);
        game.points.push(canvas.height / 2.8 + canvas.height * variations * Math.random());
    }
    game.ctx.beginPath();
    game.ctx.moveTo(canvas.width, canvas.height);
    game.ctx.lineTo(0, canvas.height);
    game.calcedPoints = game.curve(game.points);           // see below
    console.log(game.calcedPoints);
    game.ctx.closePath();
    game.ctx.fillStyle = 'green';
    game.ctx.fill();
    var n = game.height/game.qualityMap, m = game.width/game.qualityMap;
    //var game.map = [];
    for (var i = 0; i < m; i++){
        game.map[i] = [];
        for (var j = 0; j < n; j++){
            var y1 = j*game.qualityMap;
            if (y1<game.getY(i*game.qualityMap)){
                game.map[i][j] = 0;
            }
            else {
                game.map[i][j] = 1;
            }
        }
    }
    console.log(game.map);
}
game.init = function(){
    if (game.move =="random"){
        game.move = randomInteger(1,2);
    }
    if (game.war){
        game.tank1.noDrive = true;
        game.tank2.noDrive = true;
    }
    var canvas = document.getElementById('canvas');
    this.ctx = canvas.getContext("2d");
    //this.width = window.innerWidth-10;
   // this.height = window.innerHeight-10;
    canvas.width = game.width;
    canvas.height = game.height;
};


game.run= function(){
        game.update();
        game.gener();
        window.requestAnimationFrame(function () {
            game.run();
        });
};


game.update = function (){
    game.tank1.Y(game.tank1.x);
    game.tank2.Y(game.tank2.x);
if (game.tank1.Vx != 0){
        game.tank1.move();
    }
    if (game.tank2.Vx != 0){
        game.tank2.move();
    }
    if (game.shot.isAlive == true) {
        game.shot.move();
    }
    game.getEngle();
    if(game.shot.boomm) {
        game.animate();
    }
}
game.animate= function(){
    game.shot.frame++;
    if (game.shot.frame==5){
        console.log('gamesdfdsf');
        game.shot.boomm=false;
        game.shot.frame=0;
    }
}
game.updateMap = function(){
    for (var i = 0; i < game.width/game.qualityMap; i++){
        for (var j = 0; j < game.height/game.qualityMap; j++){
            var k = i*2;
            if (game.map[i][j] == 0){
                game.lineMap[k]= i*game.qualityMap;
                game.lineMap[k+1] =j*game.qualityMap;
            }
        }
    }
}
game.getY = function (x){
    var x1,x2,x3,x4, y1,y2,y3,y4 = 0;
    for (var i=0;game.calcedPoints[i]<x;i+=2){
        x1 = game.calcedPoints[i];
        x2 = game.calcedPoints[i+2];
        y1 = game.calcedPoints[i+1];
        y2 = game.calcedlineMap
    }
    var k = (y1-y2)/(x2-x1);
    return(y1);
};
game.tank1 = {
    x: game.Tank1startPosition,
    dead: false,
    noDrive:false,
    Y: function (x){
        var x1,x2,x3,x4, y1,y2,y3,y4 = 0;
        for (var i=2;game.lineMap[i]<x;i+=2){
            x1 = game.lineMap[i];
            x2 = game.lineMap[i+2];
            y1 = game.lineMap[i+1];
            y2 = game.lineMap[i+3];
        }
        var k = (y1-y2)/(x2-x1);
        return(y1);
    },
    y:300,
    Vx: 0,
    Vy: 0,
    gun: game.gun,
    engle:0,
    width: 35,
    height: 35,
    move: function () {
        if ((!game.tank1.noDrive)&&(game.tank1.x>2)&&(!game.tank1.x<1240)) {
            this.x += this.Vx;
        }
    },
    stop: function () {
        this.Vx = 0;
    },
    getEngle: function (x) {
        let x2 = x + this.tank1.width;
    },
};
game.gun1 = {
    x: function(){
        return game.tank1.x+game.tank1.width/2+7;
    },
    y: function(){
        return game.tank1.Y(game.tank1.x)-30;
    },
    engle: 0,
    power:0,
    move: function () {
    }
}


game.tank2 = {
    x: game.Tank2startPosition,
    dead: false,
    noDrive:false,
    Y: function (x){
        var x1,x2,x3,x4, y1,y2,y3,y4 = 0;
        for (var i=2;game.lineMap[i]<x;i+=2){
            x1 = game.lineMap[i];
            x2 = game.lineMap[i+2];
            y1 = game.lineMap[i+1];
            y2 = game.lineMap[i+3];
        }
        var k = (y1-y2)/(x2-x1);
        // return Math.trunc((k)*x);
        return(y1);
    },
    y:300,
    Vx: 0,
    Vy: 0,
    gun: game.gun,
    engle:0,
    width: 35,
    height: 35,
    move: function () {
        if ((!game.tank2.noDrive)&&(game.tank2.x>2)&&(!game.tank2.x<1240)) {
            this.x += this.Vx;
        }
    },
    stop: function () {
        this.Vx = 0;
    },
    getEngle: function (x) {
        let x2 = x + this.tank2.width;
    },
};
game.gun2 = {
    /*x: game.tank1.x+game.tank1.width/2+7,
    y: game.tank1.y+7,*/
    x: function(){
        return game.tank2.x+game.tank2.width/2+7;
    },
    y: function(){
        return game.tank2.Y(game.tank2.x)-30;
    },
    engle: 0,
    power:0,
    move: function () {
    }
}
game.createShot1 = function() {
    game.shot.isAlive = true
    var Angle = game.gun1.engle * TO_RADIANS+game.tank1.engle; // Угол в радианах
    var Speed = game.gun1.power; // Скорость
    var G = 9.81; // Свободное падение
    game.shot.x = game.tank1.x+10;
    game.shot.y = game.tank1.Y(game.tank1.x);
    var i = 0;
    var tick = 100, timeTick = tick / 1000;
     game.shot.vx = Speed * Math.cos(Angle),
         game.shot.vy = -Speed * Math.sin(Angle);
    game.shot.dx = game.shot.vx * timeTick;
    game.shot.dvy = G * timeTick;
}
game.createShot2 = function() {
    game.shot.isAlive = true
    var Angle = game.gun2.engle * TO_RADIANS+game.tank2.engle; // Угол в радианах
    var Speed = game.gun2.power; // Скорость
    var G = 9.81; // Свободное падение
    game.shot.x = game.tank2.x+10;
    game.shot.y = game.tank2.Y(game.tank2.x);
    var i = 0;
    var tick = 100, timeTick = tick / 1000;
    game.shot.vx = Speed * Math.cos(Angle),
        game.shot.vy = -Speed * Math.sin(Angle);
    game.shot.dx = game.shot.vx * timeTick;
    game.shot.dvy = G * timeTick;
}
game.shot = {
    isAlive: false,
    boomm:false,
    frame:-1,
    x:undefined,
    y:undefined,
    angle:-45* TO_RADIANS,
    vx:50,
    vy:9.81,
    dvx:0,
    dx:undefined,
    move: function() {
        game.shot.x += game.shot.dx;
        game.shot.vy -= game.shot.dvy;
        game.shot.y -= game.shot.vy * 100/1000;

        if (game.CheckBordersForShots()){
            game.shot.boom();
        }
        //game.checkBordersTanks();
    },
    boom: function () {
        game.shot.boomm = true;
        var x = game.shot.x+game.shot.dx;
        var vy= game.shot.vy;
        var dvy = game.shot.dvy;
        var y = game.shot.y;
        if ((game.shot.x <4)||(game.shot.x>1279)){
            game.shot.isAlive = false;
            if(game.move ==1){
                game.move=2;
            }
            else{
                game.move =1;
            }
            return;
        }
        game.destroyMap(x,y);
        if(game.move ==1){
            game.move=2;
        }
        else{
            game.move =1;
        }
        //game.shot.x = undefined;
        //game.shot.y =undefined ;
        game.shot.isAlive = false;
        game.checkDestructionTanks(x);
    }
}
game.over = function(massage){
    alert(massage)
    window.location.reload();
}
game.checkDestructionTanks =function (x) {
    if(Math.abs(x-(game.tank1.x+game.tank1.width/2))<game.tank1.width/2+10){
        game.tank1.dead=true;
        game.over("tank 1 is dead");
    }
    if(Math.abs(x-(game.tank2.x+game.tank2.width/2))<game.tank2.width/2+10){
        game.tank2.dead=true;
        game.over("tank 2 is dead");
    }

};
game.checkBordersTanks =function () {
    var x = game.shot.x;
    var y= game.shot.y;
    if(Math.abs(x-(game.tank1.x+game.tank1.width/2))<game.tank1.width/2-5){
        if (Math.abs(y-(game.tank1.Y(x)+game.tank1.height/2-5))<game.tank1.height/2-5) {
            game.tank1.dead = true;
            game.over("tank 1 is dead");
        }
    }
    if(Math.abs(x-(game.tank2.x+game.tank2.width/2))<game.tank2.width/2-5){
        if (Math.abs(y-(game.tank2.Y(x)+game.tank2.height/2))<game.tank2.height/2-5) {
            game.tank1.dead = true;
            game.over("tank 2 is dead");
        }
    }

};
game.destroyMap = function(x,y){
    console.log()
    var i = Math.trunc(x/game.qualityMap);
    var j = Math.trunc(y/game.qualityMap);
    console.log(game.map[i][j]);
    for(var b = i-Math.trunc(24*game.boom/game.qualityMap);b<i+Math.trunc(24*game.boom/game.qualityMap);b++)
    {
        for (var a = 0; a < j + 2*game.boom; a++) {
            game.map[b][a] = 0;
        }
    }
    game.updateMap();
}
game.CheckBordersForShots = function(){
    var x1, y1 = 0;
    var x = game.shot.x-game.shot.dx;
    for (var i=2;game.lineMap[i]<=x;i+=2) {
        y1 = game.lineMap[i+1]
    }
    var vy= game.shot.vy;
    var dvy = game.shot.dvy;
    var y = game.shot.y;
    vy -=dvy;
    y-=vy*100/1000;
     if (y1<y-40){
         console.log('sdfsdf')
         return true;
     }
    }
game.start = function(){
    game.init();
    game.initController();
    game.load();
    game.initMap();
    game.updateMap();
    game.run();
}
window.addEventListener("load", function(){
    game.start();
});
game.initController = function (){
    window.addEventListener("keydown", function(event) {
        if ( event.code === 'ArrowLeft' ){
            if (game.move == 1) {
                game.tank1.Vx =  -game.tank1Speed;
            }
            else{
                game.tank2.Vx =  -game.tank1Speed;
            }
        }
        else if( event.code ===  'ArrowRight'){
            if (game.move == 1) {
                game.tank1.Vx =  game.tank1Speed;
            }
            else{
                game.tank2.Vx =  game.tank1Speed;
            }
        }
        else if( event.code ===  'ArrowDown') {
            if (game.move == 1) {
                if (game.gun1.engle < 30) {game.gun1.engle+=1}
            }
            else{
                if (game.gun2.engle < 30) {game.gun2.engle+=1}
            }
        }
        else if( event.code ===  'ArrowUp') {
            if (game.move == 1) {
                if (game.gun1.engle > -210) {game.gun1.engle-=1}
            }
            else{
                if (game.gun2.engle > -210) {game.gun2.engle-=1}
            }
        }
       else if( event.code === 'Space'){
           if (game.move == 1) {
               game.createShot1();
           }
           else{
               game.createShot2();
           }
        }
        else if( event.code === 'Period'){
            if (game.move == 1) {
                game.gun1.power++;
            }
            else{
                game.gun2.power++;
            }
        }
        else if( event.code === 'Comma'){
            if (game.move == 1) {
                game.gun1.power--;
            }
            else{
                game.gun2.power--;
            }
        }
    });
    window.addEventListener("keyup", function(event) {
        if ( event.code === 'ArrowLeft' ){
            game.tank1.stop();
            game.tank2.stop();
        }
        else if( event.code ===  'ArrowRight'){
            game.tank1.stop();
            game.tank2.stop();
        }
    });
};
game.curve = function(pts, tension, numOfSegments) {

    tension = (tension != 'undefined') ? tension : 0.5;
    numOfSegments = numOfSegments ? numOfSegments : 16;

    var _pts = [], res = [], t, i, l, r = 0,
        x, y, t1x, t2x, t1y, t2y,
        c1, c2, c3, c4, st, st2, st3, st23, st32;

    _pts = pts.concat();
    _pts.unshift(pts[1]);
    _pts.unshift(pts[0]);
    _pts.push(pts[pts.length - 2]);
    _pts.push(pts[pts.length - 1]);

    l = (_pts.length - 4);
    for (i = 2; i < l; i+=2) {

        //overrides and modifies tension for each segment.
        tension = 1 * Math.random() - 0.3;

        for (t = 0; t <= numOfSegments; t++) {
            t1x = (_pts[i+2] - _pts[i-2]) * tension;
            t2x = (_pts[i+4] - _pts[i]) * tension;
            t1y = (_pts[i+3] - _pts[i-1]) * tension;
            t2y = (_pts[i+5] - _pts[i+1]) * tension;

            st = t / numOfSegments;
            st2 = st * st;
            st3 = st2 * st;
            st23 = st3 * 2;
            st32 = st2 * 3;

            c1 = st23 - st32 + 1;
            c2 = -(st23) + st32;
            c3 = st3 - 2 * st2 + st;
            c4 = st3 - st2;

            x = c1 * _pts[i]    + c2 * _pts[i+2] + c3 * t1x + c4 * t2x;
            y = c1 * _pts[i+1]  + c2 * _pts[i+3] + c3 * t1y + c4 * t2y;

            res[r++] = x;
            res[r++] = y;
        } //for t
    } //for i

    l = res.length;
    for(i=0;i<l;i+=2) this.ctx.lineTo(res[i], res[i+1]);

    return res;  //return calculated points
}
game.drawRotatedImage = function(image1,image2, x1, y1, angle1,angle2) {
    game.ctx.save();
    game.ctx.translate(x1, y1);
    game.ctx.rotate(angle1 /* TO_RADIANS*/);
   //game.ctx.drawImage(image, -(image.width/2), -(image.height));
    game.ctx.drawImage(image1, 0, 2);

    game.ctx.translate(20, 13);
    game.ctx.rotate(angle2 /* TO_RADIANS*/);

    game.ctx.drawImage(image2,0 ,-17.5);
    game.ctx.restore();
}
game.drawRotatedGun = function(image, x, y, angle) {
    game.ctx.save();
    game.ctx.translate(x, y);
    game.ctx.rotate(angle /* TO_RADIANS*/);
    game.ctx.drawImage(image, 0,-17.5);
    game.ctx.restore();
}

game.getEngle = function() {
    var y1 = game.tank1.Y(game.tank1.x);
    var y2= game.tank1.Y(game.tank1.x+game.tank1.height);
    if (Math.abs(y1-y2)>game.cross) {
        game.tank1.stop();
        game.tank1.noDrive = true;
    }
    else {
        game.tank1.engle = -Math.atan((y1 - y2) / game.tank1.width);
    }
    var y1t2 = game.tank2.Y(game.tank2.x);
    var y2t2= game.tank2.Y(game.tank2.x+game.tank2.height);
    if (Math.abs(y1t2-y2t2)>game.cross) {
        game.tank2.stop();
        game.tank2.noDrive = true;
    }
    else {
        game.tank2.engle = -Math.atan((y1t2 - y2t2) / game.tank2.width);
    }
}

game.drawEllipse =  function (coordX,coordY, sizeA,sizeB, angle) {
    game.ctx.beginPath();
    game.ctx.save(); // сохраняем стейт контекста
    game.ctx.translate(coordX, coordY); // перемещаем координаты в центр эллипса
    game.ctx.rotate(angle); // поворачиваем координатную сетку на нужный угол
    game.ctx.scale(1, sizeB/sizeA); // сжимаем по вертикали
    game.ctx.arc(0, 0, sizeA, 0, Math.PI*2); // рисуем круг
    game.ctx.restore(); // восстанавливает стейт, иначе обводка и заливка будут сплющенными и повёрнутыми
    game.ctx.strokeStyle = 'green';
    game.ctx.stroke(); // обводим
    game.ctx.closePath();
}