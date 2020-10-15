<?php
	session_start();
	require_once "chessboard.php";
	require_once "sql.php";
	
	$return = [
		"success" => false,
		"return_info" => "",
		"response" => [
			"map" => "",
			"color" => "",
			"black" => -1,
			"white" => -1,
			"round" => "",
			"winner" => -1
		]
	];

	function exit_and_echo($array, $success, $return_info){
		$array["success"] = $success;
		$array["return_info"] = $return_info;
		echo json_encode($array);
		exit();
	}

	if(!isset($_GET['action']) || !isset($_SESSION['logged_in'])) exit_and_echo($return, false, "Incorrect parameters");
	
	if($_GET['action'] == "getMap") {
		$resultMap = $database->query("SELECT * FROM `games` WHERE `status` = 1 AND (`second_player` = ".$_SESSION['logged_in']." OR `first_player` = ".$_SESSION['logged_in'].")");
		
		if($resultMap->num_rows > 0) {
			$resultMapData = $resultMap->fetch_assoc();
			
			if($_SESSION['logged_in'] == $resultMapData['first_player']) $return['response']['color'] = 'white';
			else $return['response']['color'] = 'black';
			
			if($resultMapData['map'] == "") {
				$chessboard = new Chessboard(Chessboard::generateMap());
				$database->query("UPDATE `games` SET `map` = '".$chessboard->showMapJSON()."' WHERE `games`.`id` = ".$resultMapData['id']);
				$return['response']['map'] = $chessboard->showMapRAW();
				exit_and_echo($return, true, "getMap");
			} else {
				$chessboard = new Chessboard(json_decode($resultMapData['map']));

				$whiteCount = $chessboard->countWhite();
				$blackCount = $chessboard->countBlack();

				if ($whiteCount == 0) $return['response']['winner'] = 2;
				if ($blackCount == 0) $return['response']['winner'] = 1;

				$return['response']['black'] = $blackCount;
				$return['response']['white'] = $whiteCount;
				$return['response']['round'] = $resultMapData['round'];
				$return['response']['map'] = $chessboard->showMapRAW();

				exit_and_echo($return, true, "getMap");
			}
		}
	}
	
	if($_GET['action'] == "setPawn") {
		if(!isset($_GET['pawnColor']) || !isset($_GET['sx']) || !isset($_GET['sy']) || !isset($_GET['newx']) || !isset($_GET['newy'])) exit_and_echo($return, false, "setPawn");
		
		$resultMap = $database->query("SELECT * FROM `games` WHERE `status` = 1 AND (`second_player` = ".$_SESSION['logged_in']." OR `first_player` = ".$_SESSION['logged_in'].")");
		
		if($resultMap->num_rows > 0) {
			$resultMapData = $resultMap->fetch_assoc();
			
			if($_SESSION['logged_in'] == $resultMapData['first_player']) $return['response']['color'] = 'white';
			else $return['response']['color'] = 'black';
			
			$chessboard = new Chessboard(json_decode($resultMapData['map']));
			$chessboard->setPawn($_GET['sx'], $_GET['sy'], 0);
			
			if($_GET['pawnColor'] == "white") $chessboard->setPawn($_GET['newx'], $_GET['newy'], 1);
			if($_GET['pawnColor'] == "black") $chessboard->setPawn($_GET['newx'], $_GET['newy'], 2);

			$color = "";
			if ($resultMapData['round'] == "white") $color = "black";
			else $color = "white";
			
			$database->query("UPDATE `games` SET `round` = '".$color."' WHERE `games`.`id` = ".$resultMapData['id']);
			$database->query("UPDATE `games` SET `map` = '".$chessboard->showMapJSON()."' WHERE `games`.`id` = ".$resultMapData['id']);
			$return['response']['black'] = $chessboard->countBlack();
			$return['response']['white'] = $chessboard->countWhite();
			$return['response']['round'] = $color;
			$return['response']['map'] = $chessboard->showMapRAW();
			exit_and_echo($return, true, "setPawn");
		}
	}
	
	if($_GET['action'] == "kill") {
		if(!isset($_GET['kx']) || !isset($_GET['ky']) || !isset($_GET['sx']) || !isset($_GET['sy']) || !isset($_GET['newx']) || !isset($_GET['newy'])) exit_and_echo($return, false, "kill");
		
		$resultMap = $database->query("SELECT * FROM `games` WHERE `status` = 1 AND (`second_player` = ".$_SESSION['logged_in']." OR `first_player` = ".$_SESSION['logged_in'].")");
		
		if($resultMap->num_rows > 0) {
			$resultMapData = $resultMap->fetch_assoc();
			
			if($_SESSION['logged_in'] == $resultMapData['first_player']) $return['response']['color'] = 'white';
			else $return['response']['color'] = 'black';
			
			$chessboard = new Chessboard(json_decode($resultMapData['map']));
			$chessboard->setPawn($_GET['sx'], $_GET['sy'], 0);
			$chessboard->setPawn($_GET['kx'], $_GET['ky'], 0);
			
			if($_GET['game_color'] == "white") $chessboard->setPawn($_GET['newx'], $_GET['newy'], 1);
			if($_GET['game_color'] == "black") $chessboard->setPawn($_GET['newx'], $_GET['newy'], 2);

			$color = "";
			if ($resultMapData['round'] == "white") $color = "black";
			else $color = "white";
			
			$database->query("UPDATE `games` SET `round` = '".$color."' WHERE `games`.`id` = ".$resultMapData['id']);
			
			$database->query("UPDATE `games` SET `map` = '".$chessboard->showMapJSON()."' WHERE `games`.`id` = ".$resultMapData['id']);
			$return['response']['black'] = $chessboard->countBlack();
			$return['response']['white'] = $chessboard->countWhite();
			$return['response']['map'] = $chessboard->showMapRAW();
			exit_and_echo($return, true, "kill");
		}
	}
	
	if($_GET['action'] == "quitGame") {
		$database->query("DELETE FROM `games` WHERE `status` = 1 AND (`first_player` = ".$_SESSION['logged_in']." OR `second_player` = ".$_SESSION['logged_in'].")");
		exit_and_echo($return, true, "quitGame");
	}
	
	/*$chessboard = new Chessboard(Chessboard::generateMap());
	$chessboard->renderMap();*/
?>