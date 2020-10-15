<?php
	session_start();
	require_once "php/sql.php";
?>

<!DOCTYPE html>
<html lang="pl">
	<head>
		<meta charset="UTF-8"/>
		<link href="https://fonts.googleapis.com/css?family=Josefin+Sans|Titan+One" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<link rel="stylesheet" href="style/style.css"/>
		<script src="js/warcaby.js"></script>
		<script src="js/server.js"></script>
		<script src="js/main.js"></script>
		<title>Name in progress</title>
		<script>
			const server = new Server();
		</script>
	</head>

	<body>
		<header>Warcaby</header>
		<main class="shadowLV4">
			<?php include "html/nav.php" ?>
			<article>
				<h1>Warcaby</h1>
				<div id="game_container">
					<div class="game_menu">
						<div id="game_menu_left" style="float: left; line-height: 25px;">
							Grasz pionkami <span id="game_menu_pawn_color" style="text-decoration: underline;"></span><br>
							Zbite białe: <b><span id="game_menu_white_down">0</span></b> | Zbite czarne: <b><span id="game_menu_black_down">0</span></b><br>
							Ruch: <u><span id="game_menu_round">biały</span></u>
						</div>
						<div style="float: right"><a href="#" onclick="game.exit();">Wyjdź</a></div>
						<div style="clear: both;"></div>
					</div>
				</div>
			</article>
			<footer>Kuba Janczak 2018 &copy;</footer>
		</main>
		<script>
			const game = new Game('#game_container');
		</script>
		
		<?php include "html/login_register.php" ?>
		
		<div id="msg_box" class="shadowLV3"></div>
		
		<div id="black_background"></div>
		
		<script>
			<?php
				if(isset($_SESSION['succes_msg'])) {
					echo "MsgBox.show('".$_SESSION['succes_msg']."', 2500, MsgBox.COLOR_GREEN());";
				}
			?>
		</script>
	</body>
</html>

<?php
	unset($_SESSION['login_error']);
	unset($_SESSION['register_error']);
	unset($_SESSION['succes_msg']);
?>