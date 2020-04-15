window.onload = function () {


    var canvas = document.getElementById('canvas');
    ctx = canvas.getContext("2d");

    tank = new Image();
    tank.src = 'images/tank.png';
    asd = new Image();
    asd.src = 'images/35_base.png';
    update = function () {
       //
        ctx.clearRect(0,0,640,480);
        drawRotatedImage(tank, 200,200,45);
        ctx.drawImage(asd, 100, 100, 50, 50);
        var numOfSegments = 9;                      // split horizontal space
        var segment = canvas.width / numOfSegments; // calc width of each segment
        var points = [], calcedPoints;
        var variations = 0.15;                      // adjust this: lower = less variations
        var i;

//produce some random heights across the canvas
        for(i=0; i < numOfSegments + 1; i++) {
            points.push(segment * i);
            points.push(canvas.height / 2.8 + canvas.height * variations * Math.random());
        }

//render the landscape

        ctx.beginPath();
        ctx.moveTo(canvas.width, canvas.height);
        ctx.lineTo(0, canvas.height);

        calcedPoints = curve(points);           // see below
        console.log(calcedPoints);
        ctx.closePath();
        ctx.fillStyle = 'green';
        ctx.fill();
        drawEllipse(ctx, 200,200, 50,70, 23);
    }
    run = function () {

        update();


        window.requestAnimationFrame(() => {
            run();
        });
    }
    run();
}
var TO_RADIANS = Math.PI/180;
function drawRotatedImage(image, x, y, angle) {
    ctx.save();
    ctx.translate(x, y);
    ctx.rotate(angle * TO_RADIANS);
    ctx.drawImage(image, -(image.width/2), -(image.height/2));
    ctx.restore();
}



 curve = function(pts, tension, numOfSegments) {

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
    for(i=0;i<l;i+=2) ctx.lineTo(res[i], res[i+1]);

    return res;  //return calculated points
}
function drawEllipse(ctx, coordX,coordY, sizeA,sizeB, angle) {
    ctx.beginPath();
    ctx.save(); // сохраняем стейт контекста
    ctx.translate(coordX, coordY); // перемещаем координаты в центр эллипса
    ctx.rotate(angle); // поворачиваем координатную сетку на нужный угол
    ctx.scale(1, sizesB/sizesA); // сжимаем по вертикали
    ctx.arc(0, 0, sizesA, 0, Math.PI*2); // рисуем круг
    ctx.restore(); // восстанавливает стейт, иначе обводка и заливка будут сплющенными и повёрнутыми
    ctx.strokeStyle = 'green';
    ctx.stroke(); // обводим
    ctx.closePath();
}