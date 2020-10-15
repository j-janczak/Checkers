const GAME_SIZE = 720;
const FIELD_SIZE = GAME_SIZE / 10;
const FIELD_BORDER_SIZE = 5;
const COLOR_WHITE = "#FAFAFA";
const COLOR_GRAY = "#424242";
const COLOR_SELECT = "#009688";
const COLOR_KILL = "#B71C1C";
const BORDER_SCHEME = "5px solid "+COLOR_GRAY;
var GAME_MAP = "";
var GAME_COLOR = "";

class Game {
	constructor(containerID) {
		this.containerID = containerID;
		this.checkerboardDIV = [];
		this.selectedPole = new SelectedPole();
		
		var iteration = 0;
		for (let x = 0; x < 10; x++) {
			var checkerboardDIV_X = [];
			for (let y = 0; y < 10; y++) {
				if ((x != 0 && y != 0 && x != 9 && y != 9) && (iteration % 2 != 0)) checkerboardDIV_X.push(new CheckerboardDIV(x, y, COLOR_GRAY));
				else checkerboardDIV_X.push(new CheckerboardDIV(x, y, COLOR_WHITE));
				iteration++;
			}
			this.checkerboardDIV.push(checkerboardDIV_X);
			iteration++;
		}
		
		$(this.containerID).append('<div id="checkers"></div>');
		$('#checkers').css({
			width: GAME_SIZE, 
			height: GAME_SIZE, 
			margin: '0 auto',
			backgroundColor: '#FAFAFA'
		});
		$('#checkers').addClass('shadowLV4');
		
		let t = this;
		Game.sendToServer('action=getMap', (response) => {Game.parseMap(response)});
		setInterval(() => {
			if(t.selectedPole.visible == 0) {
				Game.sendToServer('action=getMap', (response) => {Game.parseMap(response)});
			}
		}, 2000);
		
		for (let y = 0; y < 10; y++) for (let x = 0; x < 10; x++) $('#checkers').append(this.checkerboardDIV[x][y].getDiv());
		
		for (let l = 0; l < 8; l++) $(CheckerboardDIV.getIDh(1 + l, 0)).append(String.fromCharCode(65+l));
		for (let l = 0; l < 8; l++) $(CheckerboardDIV.getIDh(0, 1 + l)).append(8 - l);
	}
	
	static parseMap(r) {
		GAME_COLOR = r.response.color;
		if (GAME_COLOR == "white") $('#game_menu_pawn_color').text('białymi');
		if (GAME_COLOR == "black") $('#game_menu_pawn_color').text('czarnymi');

		$('#game_menu_white_down').text(12 - r.response.white);
		$('#game_menu_black_down').text(12 - r.response.black);

		var round_color = "";
		if (r.response.round == "white") $('#game_menu_round').text("biały");
		else $('#game_menu_round').text("czarny");
		
		var myColorID1 = 0;
		var myColorID2 = 0;
		if (GAME_COLOR == 'white') {
			myColorID1 = 1;
			myColorID2 = 3;
		}
		else if (GAME_COLOR == 'black') {
			myColorID1 = 2;
			myColorID2 = 4;
		}

		if (r.response.winner != -1) {
			let winnerInfo = "";
			if (r.response.winner == 1) {
				winnerInfo = `<span style="font-size: 3em"><b><i>Wygrał biały!<b><i><span>`;
			} else if (r.response.winner == 2) {
				winnerInfo = `<span style="font-size: 3em"><b><i>Wygrał czarny!<b><i><span>`;
			}

			$('#game_menu_left').html(winnerInfo);
		}
		
		if (r.response.map != "") {
			GAME_MAP = r.response.map;
			for (let x = 0; x < r.response.map.length; x++) {
				for (let y = 0; y < r.response.map[0].length; y++) {
					if(r.response.map[x][y] == 1 || r.response.map[x][y] == 2 || r.response.map[x][y] == 3 || r.response.map[x][y] == 4) {
						let pawn = ""
						let pawnColor = ""
						
						if (r.response.map[x][y] == 1) pawnColor = "style/img/checkers/pawnWhite.png";
						else if (r.response.map[x][y] == 2) pawnColor = "style/img/checkers/pawnBlack.png";
						else if (r.response.map[x][y] == 3) pawnColor = "style/img/checkers/pawnWhiteD.png";
						else if (r.response.map[x][y] == 4) pawnColor = "style/img/checkers/pawnBlackD.png";
						
						if (r.response.map[x][y] == myColorID1 && r.response.round == GAME_COLOR) {
							pawn = '<img class="game_pawn" onclick="game.onPawnClick('+x+','+y+', false)" style="cursor: pointer;" src="'+pawnColor+'"/>';
						} else if (r.response.map[x][y] == myColorID2  && r.response.round == GAME_COLOR) {
							pawn = '<img class="game_pawn" onclick="game.onPawnClick('+x+','+y+', true)" style="cursor: pointer;" src="'+pawnColor+'"/>';
						} else {
							pawn = '<img class="game_pawn" src="'+pawnColor+'"/>';
						}
					
						$(CheckerboardDIV.getIDh(x + 1,y + 1)).empty();
						$(CheckerboardDIV.getIDh(x + 1,y + 1)).append(pawn);
					} else if(r.response.map[x][y] == 0) {
						$(CheckerboardDIV.getIDh(x + 1,y + 1)).empty();
					}
				}
			}
		}
	}
	
	onPawnClick(x, y, damka) {
		if (!damka) this.selectedPole.set(x, y);
		else this.selectedPole
	}
	
	exit() {
		Game.sendToServer('action=quitGame', (r) => {
			if (r.success = true) location.href="index.php";
		});
	}
	
	static sendToServer(param, callback) {
		let server = new XMLHttpRequest();
		server.open('GET', 'php/game_server.php?'+param);
		server.addEventListener('readystatechange', function() {
			if (this.readyState == 0) {
				//Błąd połączenia
			}
			if ( this.readyState == 4) {
				callback(JSON.parse(this.responseText));
			}
		});
		server.send();
	}
}

class CheckerboardDIV {
	constructor(x, y, color) {
		this.x = x;
		this.y = y;
		this.color = color;
	}
	
	getDiv() {
		let div = '<div class="checkerboardDIV" id="'+CheckerboardDIV.getID(this.x,this.y)+'"></div>';
		div = $(div).css({
			width: FIELD_SIZE,
			height: FIELD_SIZE,
			backgroundColor: this.color,
			position: 'relative',
			boxSizing: 'border-box',
			float: 'left'
		});
		
		if(this.y == 0 && this.x > 0 && this.x < 9) div = $(div).css('border-bottom', BORDER_SCHEME);
		if(this.y == 9 && this.x > 0 && this.x < 9) div = $(div).css('border-top', BORDER_SCHEME);
		if(this.x == 0 && this.y > 0 && this.y < 9) div = $(div).css('border-right', BORDER_SCHEME);
		if(this.x == 9 && this.y > 0 && this.y < 9) div = $(div).css('border-left', BORDER_SCHEME);
		
		if(this.x == 0 && this.y == 0) $(div).append('<div style="right: 0; bottom: 0; position: absolute; width: '+FIELD_BORDER_SIZE+'px; height: '+FIELD_BORDER_SIZE+'px; background-color: '+COLOR_GRAY+'"></div>');
		if(this.x == 9 && this.y == 0) $(div).append('<div style="left: 0; bottom: 0; position: absolute; width: '+FIELD_BORDER_SIZE+'px; height: '+FIELD_BORDER_SIZE+'px; background-color: '+COLOR_GRAY+'"></div>');
		if(this.x == 0 && this.y == 9) $(div).append('<div style="right: 0; top: 0; position: absolute; width: '+FIELD_BORDER_SIZE+'px; height: '+FIELD_BORDER_SIZE+'px; background-color: '+COLOR_GRAY+'"></div>');
		if(this.x == 9 && this.y == 9) $(div).append('<div style="left: 0; top: 0; position: absolute; width: '+FIELD_BORDER_SIZE+'px; height: '+FIELD_BORDER_SIZE+'px; background-color: '+COLOR_GRAY+'"></div>');

		return div;
	}
	
	static getID(x, y) {
		return("CheckerboardDIV_x"+x+"y"+y);
	}
	
	static getIDh(x, y) {
		return("#CheckerboardDIV_x"+x+"y"+y);
	}
	
	static getIDPawnPos(x, y) {
		return("#CheckerboardDIV_x"+(x + 1)+"y"+(y + 1));
	}
	
	static getIDdivPos(x, y) {
		return("#CheckerboardDIV_x"+(x - 1)+"y"+(y - 1));
	}
}

class SelectedPole {
	constructor () {
		this.visible = 0;
		this.x = 0;
		this.y = 0;
		this.selectedPoles = [
			{available: 0,
			 x: 0,
			 y: 0},
			{available: 0,
			 x: 0,
			 y: 0}
		];
	}
	
	set(x, y) {
		if (this.visible == 1) {
			if (x == this.x && y == this.y) {
				this.visible = 0;
				this.clear();
			} else {
				this.clear();
				this._setPole(x, y);
			}
		} else {
			this._setPole(x, y);
		}
	}
	
	updatePawn(sx, sy, newx, newy) {
		var param = "action=setPawn&pawnColor="+GAME_COLOR+"&sx="+sx+"&sy="+sy+"&newx="+newx+"&newy="+newy;
		this.clear();
		Game.sendToServer(param, (r) => {
			Game.parseMap(r);
		});
	}

	kill(kx, ky, sx, sy, newx, newy) {
		var param = `action=kill&kx=${kx}&ky=${ky}&sx=${sx}&sy=${sy}&newx=${newx}&newy=${newy}&game_color=${GAME_COLOR}`;
		this.clear();
		Game.sendToServer(param, (r) => {
			Game.parseMap(r);
		});
	}
	
	_setPole(x, y) {
		let kill = false;
		if(GAME_COLOR == "black") {
			if (x - 1 > -1 && y - 1 > -1) {
				if ((GAME_MAP[x - 1][y - 1] == 1 && GAME_MAP[x - 2][y - 2] == 0) || (GAME_MAP[x + 1][y - 1] == 1 && GAME_MAP[x + 2][y - 2] == 0)) kill = true;
				if (GAME_MAP[x - 1][y - 1] == 1 && GAME_MAP[x - 2][y - 2] == 0) {
					this.selectedPoles[0] = {available: 1, x: (x - 2), y: (y - 2)}
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[0].x, this.selectedPoles[0].y)).css(
						{backgroundColor: COLOR_KILL}
					);
					
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[0].x, this.selectedPoles[0].y)).html(
						'<img class="game_pawn_placeholder" onclick="game.selectedPole.kill('+(this.selectedPoles[0].x + 1)+', '+(this.selectedPoles[0].y + 1)+', '+(this.selectedPoles[0].x + 2)+', '+(this.selectedPoles[0].y + 2)+', '+(this.selectedPoles[0].x)+', '+(this.selectedPoles[0].y)+')" src="style/img/checkers/pawnBlack.png"/>'
					);
					this.x = x;
					this.y = y;
					this.visible = 1;
				} else if (GAME_MAP[x - 1][y - 1] == 0 && !kill) {
					this.selectedPoles[0] = {available: 1, x: (x - 1), y: (y - 1)}
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[0].x, this.selectedPoles[0].y)).css(
						{backgroundColor: COLOR_SELECT}
					);
					
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[0].x, this.selectedPoles[0].y)).html(
						'<img class="game_pawn_placeholder" onclick="game.selectedPole.updatePawn('+x+','+y+','+(this.selectedPoles[0].x)+','+(this.selectedPoles[0].y)+')" src="style/img/checkers/pawnBlack.png"/>'
					);
					
					this.x = x;
					this.y = y;
					this.visible = 1;
				}
			}
			if (x + 1 < 8 && y - 1 < 8) {
				if (GAME_MAP[x + 1][y - 1] == 1 && GAME_MAP[x + 2][y - 2] == 0) {
					this.selectedPoles[1] = {available: 1, x: (x + 2), y: (y - 2)}
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[1].x, this.selectedPoles[1].y)).css(
						{backgroundColor: COLOR_KILL}
					);
					
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[1].x, this.selectedPoles[1].y)).html(
						'<img class="game_pawn_placeholder" onclick="game.selectedPole.kill('+(this.selectedPoles[1].x - 1)+', '+(this.selectedPoles[1].y + 1)+', '+(this.selectedPoles[1].x - 2)+', '+(this.selectedPoles[1].y + 2)+', '+(this.selectedPoles[1].x)+', '+(this.selectedPoles[1].y)+')" src="style/img/checkers/pawnBlack.png"/>'
					);
					this.x = x;
					this.y = y;
					this.visible = 1;
				} else if (GAME_MAP[x + 1][y - 1] == 0 && !kill) {
					this.selectedPoles[1] = {available: 1, x: (x + 1), y: (y - 1)}
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[1].x, this.selectedPoles[1].y)).css(
						{backgroundColor: COLOR_SELECT}
					);
					
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[1].x, this.selectedPoles[1].y)).html(
						'<img class="game_pawn_placeholder" onclick="game.selectedPole.updatePawn('+x+','+y+','+(this.selectedPoles[1].x)+','+(this.selectedPoles[1].y)+')" src="style/img/checkers/pawnBlack.png"/>'
					);
					
					this.x = x;
					this.y = y;
					this.visible = 1;
				}
			}
		} else {
			if (x - 1 > -1 && y + 1 > 0) {
				if ((GAME_MAP[x - 1][y + 1] == 2 && GAME_MAP[x - 2][y + 2] == 0) || (GAME_MAP[x + 1][y + 1] == 2 && GAME_MAP[x + 2][y + 2] == 0)) kill = true;
				if (GAME_MAP[x - 1][y + 1] == 2 && GAME_MAP[x - 2][y + 2] == 0) {
					this.selectedPoles[0] = {available: 1, x: (x - 2), y: (y + 2)}
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[0].x, this.selectedPoles[0].y)).css(
						{backgroundColor: COLOR_KILL}
					);
					
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[0].x, this.selectedPoles[0].y)).html(
						'<img class="game_pawn_placeholder" onclick="game.selectedPole.kill('+(this.selectedPoles[0].x + 1)+', '+(this.selectedPoles[0].y - 1)+', '+(this.selectedPoles[0].x + 2)+', '+(this.selectedPoles[0].y - 2)+', '+(this.selectedPoles[0].x)+', '+(this.selectedPoles[0].y)+')" src="style/img/checkers/pawnBlack.png"/>'
					);
					this.x = x;
					this.y = y;
					this.visible = 1;
				} else if (GAME_MAP[x - 1][y + 1] == 0 && !kill) {
					this.selectedPoles[0] = {available: 1, x: (x - 1), y: (y + 1)}
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[0].x, this.selectedPoles[0].y)).css(
						{backgroundColor: COLOR_SELECT}
					);
					
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[0].x, this.selectedPoles[0].y)).html(
						'<img class="game_pawn_placeholder" onclick="game.selectedPole.updatePawn('+x+','+y+','+this.selectedPoles[0].x+','+this.selectedPoles[0].y+')" src="style/img/checkers/pawnWhite.png"/>'
					);
					this.x = x;
					this.y = y;
					this.visible = 1;
				}
			}
			if (x + 1 < 8 && y + 1 < 8) {
				if (GAME_MAP[x + 1][y + 1] == 2 && GAME_MAP[x + 2][y + 2] == 0) {
					this.selectedPoles[1] = {available: 1, x: (x + 2), y: (y + 2)}
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[1].x, this.selectedPoles[1].y)).css(
						{backgroundColor: COLOR_KILL}
					);
					
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[1].x, this.selectedPoles[1].y)).html(
						'<img class="game_pawn_placeholder" onclick="game.selectedPole.kill('+(this.selectedPoles[1].x - 1)+', '+(this.selectedPoles[1].y - 1)+', '+(this.selectedPoles[1].x - 2)+', '+(this.selectedPoles[1].y - 2)+', '+(this.selectedPoles[1].x)+', '+(this.selectedPoles[1].y)+')" src="style/img/checkers/pawnWhite.png"/>'
					);
					this.x = x;
					this.y = y;
					this.visible = 1;
				} else if (GAME_MAP[x + 1][y + 1] == 0 && !kill) {
					this.selectedPoles[1] = {available: 1, x: (x + 1), y: (y + 1)}
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[1].x, this.selectedPoles[1].y)).css(
						{backgroundColor: COLOR_SELECT}
					);
					
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[1].x, this.selectedPoles[1].y)).html(
						'<img class="game_pawn_placeholder" onclick="game.selectedPole.updatePawn('+x+','+y+','+this.selectedPoles[1].x+','+this.selectedPoles[1].y+')" src="style/img/checkers/pawnWhite.png"/>'
					);
					this.x = x;
					this.y = y;
					this.visible = 1;
				} 
			}
		}
	}

	clear() {
		if(GAME_COLOR == "black") {
			if(this.y - 1 > -1 && this.x - 1 > -1 && this.selectedPoles[0].available == 1) {
				if(this.selectedPoles[0].x != -1 && this.selectedPoles[0].y != -1) {
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[0].x, this.selectedPoles[0].y)).css({backgroundColor: COLOR_GRAY});
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[0].x, this.selectedPoles[0].y)).empty();
				}
				this.selectedPoles[0] = {available: 0, x: -1, y: -1}
			}
			if(this.y - 1 > -1 && this.x + 1 < 8 && this.selectedPoles[1].available == 1) {
				if(this.selectedPoles[1].x != -1 && this.selectedPoles[1].y != -1) {
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[1].x, this.selectedPoles[1].y)).css({backgroundColor: COLOR_GRAY});
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[1].x, this.selectedPoles[1].y)).empty();
				}
				this.selectedPoles[1] = {available: 0, x: -1, y: -1}
			}
		}
		else {
			if(this.y + 1 < 8 && this.x - 1 > -1 && this.selectedPoles[0].available == 1) {
				if(this.selectedPoles[0].x != -1 && this.selectedPoles[0].y != -1) {
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[0].x, this.selectedPoles[0].y)).css({backgroundColor: COLOR_GRAY});
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[0].x, this.selectedPoles[0].y)).empty();
				}
				this.selectedPoles[0] = {available: 0, x: -1, y: -1}
			}
			if(this.y + 1 < 8 && this.x + 1 < 8 && this.selectedPoles[1].available == 1) {
				if(this.selectedPoles[1].x != -1 && this.selectedPoles[1].y != -1) {
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[1].x, this.selectedPoles[1].y)).css({backgroundColor: COLOR_GRAY});
					$(CheckerboardDIV.getIDPawnPos(this.selectedPoles[1].x, this.selectedPoles[1].y)).empty();
				}
				this.selectedPoles[1] = {available: 0, x: -1, y: -1}
			}
		}
		this.x = -1;
		this.y = -1;
		this.visible = 0;
	}
}