/*
 ****************************************************
 * maze.js
 *
 * Author: <brandon.blodget@gmail.com>
 *
 * Copyright 2011 Brandon Blodget.  
 *
 * This script defines an API for drawing a
 * Micromouse maze and controlling a mouse
 * inside the generated maze.
 * It requires an html5 capable web browser.
 *
 * License:
 * 
 * This file is part of "MicromouseSim"
 *
 * "MicromouseSim" is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * "MicromouseSim" is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with "MicromouseSim".  If not, see <http://www.gnu.org/licenses/>.
 *
 ****************************************************
 */

// The Micromouse object.  This is the only variable
// we export to the global namespace.  The API is
// made available through this object.
var mouse;
if (!mouse) {
	mouse = {};
}

var cWidth;	// the width of the maze in cells
var cHeight;	// the height of the maze in cells
var canvas; // the html5 canvas we draw on
var ctx;	// the canvas context
var pCellWidth; // width of a cell in pixels
var pCellHeight;	// height of a cell in pixels

var mouse_new = [];
origin_pos = [[0, 0], [0, 15], [15, 0], [15, 15]];
mouse_new[0] = new Mouse(origin_pos[0], "S");
mouse_new[1] = new Mouse(origin_pos[1], "N");
mouse_new[2] = new Mouse(origin_pos[2], "S");
mouse_new[3] = new Mouse(origin_pos[3], "N");

var runTimer; // The timer of updating the canvas

function Mouse(pos, dir) {
	var mRadius;					// mouse radius.
	this.origin_pos = pos;

	this.x = function() {
		return this.cMouseX;
	};

	this.y = function() {
		return this.cMouseY;
	};

	this.heading = function() {
		return this.direction;
	};

	this.cell2px = function() {
		return ((this.cMouseX * pCellWidth) + (pCellWidth/2));
	}

	this.cell2py = function() {
		return ((this.cMouseY * pCellHeight) + (pCellHeight/2));
	}

	this.head2angle = function() {
		switch(this.direction) {
			case "N" : return -90;
			case "E" : return 0;
			case "S" : return 90;
			case "W" : return 180;
		}
		return 0;
	}

	this.setHomePosition = function() {
		this.cMouseX = this.origin_pos[0];
		this.cMouseY = this.origin_pos[1];
		this.pMouseX = this.cell2px();
		this.pMouseY = this.cell2py();
		this.aMouseDir = this.head2angle();
	}

	this.erase = function() {
		var px, py;

		px = this.pMouseX - (pCellWidth-2)/2;
		py = this.pMouseY - (pCellHeight-2)/2;

		ctx.clearRect(px, py, pCellWidth-3, pCellHeight-3);
	}

	this.draw = function() {
		ctx.beginPath();
		ctx.arc(this.pMouseX, this.pMouseY, this.mRadius, rads(this.aMouseDir),
			rads(this.aMouseDir + 360),false); // Outer circle
		ctx.lineTo(this.pMouseX, this.pMouseY);
		ctx.closePath();
		ctx.strokeStyle = "#000";
		ctx.stroke();
	}

	this.updateCoordinates = function(x, y, direction) {
		this.erase();
		this.cMouseX = x;
		this.cMouseY = y;
		this.pMouseX = this.cell2px();	// mouse x pos in pixels
		this.pMouseY = this.cell2py();	// mouse y pos in pixels
		this.direction = direction;
		this.aMouseDir = this.head2angle();
		this.draw();
	}

	this.cMouseX = pos[0];			// mouse x pos in cells
	this.cMouseY = pos[1];			// mouse y pos in cells
	this.pMouseX = this.cell2px();	// mouse x pos in pixels
	this.pMouseY = this.cell2py();	// mouse y pos in pixels
	this.direction = dir;			// "N", "E", "S", or "W"
	this.aMouseDir = this.head2angle();
}

function newMaze(ss_button, maze_sel) {
	cWidth = 16;	
	cHeight = 16;	

	canvas = document.getElementById("maze");
	ctx = canvas.getContext("2d");

	pWidth = canvas.width;
	pHeight = canvas.height;

	pCellWidth = pWidth / cWidth;
	pCellHeight = pHeight / cHeight;

	// init mouse starting mostion
	// bottom left square
	for (var i = 0; i < mouse_new.length; i++) {
		mouse_new[i].setHomePosition();
		if (pCellWidth > pCellHeight) {
			mouse_new[i].mRadius = Math.floor(pCellHeight/2) - 5;
		} else {
			mouse_new[i].mRadius = Math.floor(pCellWidth/2) - 5;
		}
	}
	loadMaze(maze_sel);
}

function loadMaze(maze_selp) {
	var maze_json = "mazes_json/" + maze_selp + ".json";

	maze_sel = "loading";

	// change menu selection
	$("#maze_sel").val(maze_selp).attr('selected','selected');

	$.getJSON(maze_json, function(json) {
		maze_sel = maze_selp;
		maze = json;
		drawMaze();
		drawMice();
	});

};

function rads(degrees) {
	return (Math.PI/180)*degrees;
}

function drawMaze() {
	var x;
	var y;
	var px;
	var py;
	var code;

	// clear canvas
	canvas.width = canvas.width;

	for (y=0;y<cHeight;y++) {
		for (x=0;x<cWidth;x++) {
			code = maze[y][x];
			px = x * pCellWidth;
			py = y * pCellHeight;

			// north wall
			ctx.beginPath();
			ctx.moveTo(px,py);
			ctx.lineTo(px+pCellWidth,py);
			if (code.indexOf("N") !== -1) {
				ctx.strokeStyle="white";
			} else {
				ctx.strokeStyle="blue";
			}
			ctx.stroke();

			// east wall
			ctx.beginPath();
			ctx.moveTo(px+pCellWidth,py);
			ctx.lineTo(px+pCellWidth,py+pCellHeight);
			if (code.indexOf("E") !== -1) {
				ctx.strokeStyle="white";
			} else {
				ctx.strokeStyle="blue";
			}
			ctx.stroke();

			// south wall
			ctx.beginPath();
			ctx.moveTo(px+pCellWidth,py+pCellHeight);
			ctx.lineTo(px,py+pCellHeight);
			if (code.indexOf("S") !== -1) {
				ctx.strokeStyle="white";
			} else {
				ctx.strokeStyle="blue";
			}
			ctx.stroke();

			// west wall
			ctx.beginPath();
			ctx.moveTo(px,py+pCellHeight);
			ctx.lineTo(px,py);
			if (code.indexOf("W") !== -1) {
				ctx.strokeStyle="white";
			} else {
				ctx.strokeStyle="blue";
			}
			ctx.stroke();
		}
	}
}

function drawMice() {
	for (var i = 0; i < mouse_new.length; i++) {
		mouse_new[i].draw();
	}
}

function resetMice() {
	mouse_new[0].updateCoordinates(	mouse_new[0].origin_pos[0],
									mouse_new[0].origin_pos[1], "S");
	mouse_new[1].updateCoordinates(	mouse_new[1].origin_pos[0],
									mouse_new[1].origin_pos[1], "N");
	mouse_new[2].updateCoordinates(	mouse_new[2].origin_pos[0],
									mouse_new[2].origin_pos[1], "S");
	mouse_new[3].updateCoordinates(	mouse_new[3].origin_pos[0],
									mouse_new[3].origin_pos[1], "N");
}

function updateCoordinates(sessionId) {
	runTimer = setInterval(function(){
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var strCoordinates = this.responseText;
				var coordinates = strCoordinates.split(',');
				for (var i = 0; i < mouse_new.length; i++) {
					var x = parseInt(coordinates[3*i]);
					var y = parseInt(coordinates[3*i+1]);
					var direction;
					switch(coordinates[3*i+2]) {
						case "up":
							direction = "N";
							break;
						case "down":
							direction = "S";
							break;
						case "left":
							direction = "W";
							break;
						case "right":
							direction = "E";
							break;
						default:
							direction = "N";
					}
					mouse_new[i].updateCoordinates(x, y, direction);
				}
			}
		};
		xhttp.open("GET", "queryRobot.php?session_id="+sessionId, true);
		xhttp.send();
	}, 100);
};

function removeTimer() {
	if (runTimer) {
		clearInterval(runTimer);
	}
}

