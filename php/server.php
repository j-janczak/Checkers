<?php
	session_start();
	require "sql.php";
	
	$return = [
		"success" => false,
		"return_info" => "",
		"response" => [
			"players" => [],
			"invite" => [],
			"startGame" => -1
		]
	];

	function exit_and_echo($array, $success, $return_info){
		$array["success"] = $success;
		$array["return_info"] = $return_info;
		echo json_encode($array);
		exit();
	}

	if(!isset($_GET['action'])) exit_and_echo($return, false, "Incorrect parameters");
	
	//##############################Functions###############################
	
	if(!isset($_SESSION['logged_in'])) exit_and_echo($return, false, "User not logged in");
	
	if($_GET['action'] == 'updateStatus') {
		$database->query("UPDATE `users` SET `last_seen` = '".time()."' WHERE `users`.`id` = ".$_SESSION['logged_in'].";");
		
		$resultActiveUser = $database->query("SELECT * FROM `users` WHERE last_seen > ".(time()-5)." AND `id` != ".$_SESSION['logged_in']);
		while ($user_data = $resultActiveUser->fetch_assoc()) {
			$user = [
				"id" => $user_data['id'],
				"nick" => $user_data['login']
			];
			array_push($return['response']['players'], $user);
		}
		
		$resultInvite = $database->query("SELECT * FROM `games` WHERE `status` = 0 AND `second_player` = ".$_SESSION['logged_in']);
		if($resultInvite->num_rows > 0) {
			while($sendingUserID = $resultInvite->fetch_assoc()) {
				$sendingUser = $database->query("SELECT * FROM `users` WHERE `id` = ".$sendingUserID['first_player'])->fetch_assoc();
				$user = [
					"id" => $sendingUser['id'],
					"nick" => $sendingUser['login']
				];
				array_push($return['response']['invite'], $user);
			}
		}
		
		$resultStartGame = $database->query("SELECT * FROM `games` WHERE `status` = 1 AND (`second_player` = ".$_SESSION['logged_in']." OR `first_player` = ".$_SESSION['logged_in'].")");
		if($resultStartGame->num_rows > 0) {
			$resultStartGameData = $resultStartGame->fetch_assoc();
			
			$opponentID = "";
			if($resultStartGameData['first_player'] == $_SESSION['logged_in']) $opponentID = $resultStartGameData['second_player'];
			else $opponentID = $resultStartGameData['first_player'];
			
			$opponent = $database->query("SELECT * from `users` WHERE `id` = ".$opponentID)->fetch_assoc();
			
			$game = [
				"slot" => $resultStartGameData['id'],
				"opponent" => [
					"id" => $opponent['id'],
					"nick" => $opponent['login']
				]
			];
			$return['response']['startGame'] = $game;
		}
		
		exit_and_echo($return, true, "updateStatus");
	
	}
	
	if($_GET['action'] == 'inviteToGame') {
		if(!isset($_GET['playerID'])) exit_and_echo($return, false, "Missing inviteID");
		else {		
			$database->query(
			sprintf("INSERT INTO `games` (`id`, `status`, `first_player`, `second_player`, `active_time`) VALUES (NULL, '0', '".$_SESSION['logged_in']."', '%s', '".time()."');",
			mysqli_real_escape_string($database, $_GET['playerID'])));
		}
	}
	
	if($_GET['action'] == 'removeInvite') {
		if(!isset($_GET['playerID'])) exit_and_echo($return, false, "Missing inviteID");
		else {
			$database->query(sprintf(
				"DELETE FROM `games` WHERE `games`.`second_player` = ".$_SESSION['logged_in']." AND `games`.`first_player` = %s",
				mysqli_real_escape_string($database, $_GET['playerID'])));
			exit_and_echo($return, true, "removeInvite");
		}
	}
	
	if($_GET['action'] == 'acceptInvite') {
		if(!isset($_GET['playerID'])) exit_and_echo($return, false, "Missing inviteID");
		else {
			$database->query(sprintf(
				"UPDATE `games` set `status` = 1 WHERE `games`.`second_player` = ".$_SESSION['logged_in']." AND `games`.`first_player` = %s",
				mysqli_real_escape_string($database, $_GET['playerID'])));
			exit_and_echo($return, true, "acceptInvite");
		}
	}
?>