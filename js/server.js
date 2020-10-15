class Server {
	constructor() {
		let t = this;
		t.updateStatus()
		setInterval(() => {t.updateStatus()}, 3000);
	}
	
	updateStatus() {
		let t = this; 
		this.sendToServer("action=updateStatus", (response) => {
			let html = '';
			for (let i = 0; i < response.response.players.length; i++) {
				html += `
					<div class="users_list_container">
						<div class="users_list_container_cell" style="float: left;"><img src="style/img/avatar.png"/></div>
						<div class="users_list_container_cell" style="float: left; line-height: 75px;">`+response.response.players[i].nick+`</div>
						<div class="users_list_container_cell" style="float: right; padding: 0px;">`;
							html += `<a href="#" onClick="server.inviteToGame(`+response.response.players[i].id+`)">Zagraj</a>`
				html += `
						</div>
						<div style="clear: both;"></div>
					</div>
				`;
			}
			$('#playerList').html(html);
			
			html = '';
			for (let i = 0; i < response.response.invite.length; i++) {
				html += `
					<div class="invites_list_container">
						<div class="invites_list_container_cell" style="float: left; line-height: 32px">`+response.response.invite[i].nick+`</div>
						<div class="invites_list_container_cell" style="float: right;">`;
							html += `<a href="#" class="invite_accept" onClick="server.acceptInvite(`+response.response.invite[i].id+`)">Akceptuj</a>
									 <a href="#" class="invite_deny" onClick="server.removeInvite(`+response.response.invite[i].id+`)">X</a>`
				html += `
						</div>
						<div style="clear: both;"></div>
					</div>
				`;
			}
			$('#inviteList').html(html);
			
			
			if (response.response.startGame != -1 && window.location.href.search("checkers") == -1) {
				location.href="checkers.php";
				//console.log(response.response.startGame.slot);
			}
			
			if (response.response.startGame == -1 && window.location.href.search("checkers") > -1) {
				location.href="index.php";
			}
		});
	}
	
	acceptInvite(playerID) {
		this.sendToServer("action=acceptInvite&playerID="+playerID, (response) => {
			if(!response.success) MsgBox.show("Error", 1000, MsgBox.COLOR_RED);
		});
	}
	
	inviteToGame(id) {
		MsgBox.show("Wysłano zaproszenie", 1500, MsgBox.COLOR_GREEN());
		this.sendToServer("action=inviteToGame&playerID="+id, (response) => {
			if(!response.success) MsgBox.show("Error", 1000, MsgBox.COLOR_RED);
		});
	}
	
	removeInvite(playerID) {
		this.sendToServer("action=removeInvite&playerID="+playerID, (response) => {
			if(!response.success) MsgBox.show("Error", 1000, MsgBox.COLOR_RED);
		});
		this.updateStatus();
	}
	
	sendToServer(param, callback) {
		let server = new XMLHttpRequest();
		server.open('GET', 'php/serwer.php?'+param);
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