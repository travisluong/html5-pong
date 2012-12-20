<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="description" content="HTML5 Pong is a simple HTML5 Canvas and JavaScript game made by Travis Luong">
	<meta name="keywords" content="html5, pong, indie, browser-based, javascript, arcade, retro, oldschool, travis luong">
	<title>HTML5 Pong</title>
	<link rel="stylesheet" href="../css/reset.css">
	<link rel="stylesheet" href="../css/global.css?v=1">
	<link rel="stylesheet" href="css/style.css?v=1">
</head>
<body>
	<?php include('../header.php'); ?>
	<div id="stage"></div>
	<div id="instructions">
		<p id="player1">
			Player 1:
			<br> w: up
			<br> s: down
		</p>
		<p id="player2">
			Player 2:
			<br> up arrow: up
			<br> down arrow: down
		</p>
	</div>
	<script type="text/javascript">
// initialize globals
var WIDTH = 600;
var HEIGHT = 400;
var SCORE1 = 0;
var SCORE2 = 0;
var PAD_WIDTH = 8;
var PAD_HEIGHT = 80;
var HALF_PAD_WIDTH = PAD_WIDTH / 2;
var HALF_PAD_HEIGHT = PAD_HEIGHT / 2;
var PADDLE_SPEED = 256;

// create the canvas
var canvas = document.createElement("canvas");
var ctx = canvas.getContext("2d");
canvas.width = WIDTH;
canvas.height = HEIGHT;
document.getElementById("stage").appendChild(canvas);

// load audio
var snd = new Audio("sounds/blip.wav");

var snd2 = new Audio("sounds/blip2.wav");

// game objects
var ball = {
	x: 0,
	y: 0,
	velx: 0,
	vely: 0,
	radius: 20
};

var paddle1 = {
	vely: PADDLE_SPEED,
	y: HEIGHT / 2 - HALF_PAD_HEIGHT
};

var paddle2 = {
	vely: PADDLE_SPEED,
	y: HEIGHT / 2 - HALF_PAD_HEIGHT
};

// handle keyboard controls
var keysDown = {};

addEventListener("keydown", function (e) {
	keysDown[e.keyCode] = true;
	switch(e.keyCode){
        case 37: case 39: case 38:  case 40: // arrow keys
        case 32: e.preventDefault(); break; // space
        default: break; // do not block other keys
    }
}, false);

addEventListener("keyup", function (e) {
	delete keysDown[e.keyCode];
}, false);

// spawns a ball
var ball_init = function (right) {
	var vely = Math.floor(Math.random() * 200 + 150);
	var velx = Math.floor(Math.random() * 60 + 150);

	if (right === false) {
		velx *= -1;
	}

	ball.x = 300;
	ball.y = 200;
	ball.velx = velx;
	ball.vely = vely;
};

// reset the game
var reset = function () {
	ball_init(false);
};

// update game objects
var update = function (modifier) {
	if (38 in keysDown) { // player holding up
		if (paddle2.y > 0) {
			paddle2.y -= paddle2.vely * modifier;
		}
	}
	if (40 in keysDown) { // player holding down
		if (paddle2.y < HEIGHT - PAD_HEIGHT) {
			paddle2.y += paddle2.vely * modifier;
		}
	}
	if (87 in keysDown) { // player holding W
		if (paddle1.y > 0) {
			paddle1.y -= paddle1.vely  * modifier;
		}
	}
	if (83 in keysDown) { // player holding S
		if (paddle1.y < HEIGHT - PAD_HEIGHT) {
			paddle1.y += paddle1.vely * modifier;
		}
	}
	if (ball.x + ball.radius >= WIDTH - PAD_WIDTH) {
		if (ball.y >= paddle2.y && ball.y <= paddle2.y + PAD_HEIGHT) {
			ball.x = WIDTH - PAD_WIDTH - ball.radius - 1;
			ball.velx *= -1.1;
			snd.play();
		} else {
			ball_init(false);
			SCORE1 += 1;
		}
	} else if (ball.x - ball.radius <= PAD_WIDTH) {
		if (ball.y >= paddle1.y && ball.y <= paddle1.y + PAD_HEIGHT) {
			ball.x = PAD_WIDTH + ball.radius + 1;
			ball.velx *= -1.1;
			snd.play();
		} else {
			ball_init(true);
			SCORE2 += 1;
		}
	}
	if (ball.y <= ball.radius) {
		ball.y = ball.radius + 1;
		ball.vely = -ball.vely;
		snd2.play();
	} else if (ball.y >= HEIGHT - ball.radius) {
		ball.y = HEIGHT - ball.radius - 1;
		ball.vely = -ball.vely;
		snd2.play();
	}
	// update ball
	ball.x += ball.velx * modifier;
	ball.y += ball.vely * modifier;
};

// draw everything
var render = function () {
	// draw background
	ctx.fillStyle = "black";
	ctx.fillRect(0, 0, canvas.width, canvas.height);

	// draw mid line
	ctx.fillStyle = "white";
	ctx.fillRect(canvas.width / 2 - 1, 0, 2, canvas.height);

	// draw ball
	ctx.beginPath();
	ctx.arc(ball.x, ball.y, ball.radius, 0, Math.PI+(Math.PI*2)/2, false);
	ctx.fill();

	// draw paddles
	ctx.fillStyle = "red";
	ctx.fillRect(0, paddle1.y, PAD_WIDTH, PAD_HEIGHT);
	ctx.fillRect(WIDTH - PAD_WIDTH, paddle2.y, PAD_WIDTH, PAD_HEIGHT);

	// draw score
	ctx.fillStyle = "rgb(250, 250, 250)";
	ctx.font = "24px Helvetica";
	ctx.textAlign = "left";
	ctx.textBaseline = "top";
	ctx.fillText(SCORE1, 32, 32);
	ctx.fillText(SCORE2, 558, 32);
};

// the main game loop
var main = function () {
	var now = Date.now();
	var delta = now - then;

	update(delta / 1000);
	render();

	then = now;
};

// let's play this game!
reset();
var then = Date.now();
setInterval(main, 1); // execute as fast as possible
</script>
<?php include('../footer.php'); ?>
</body>
</html>