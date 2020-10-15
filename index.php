<?php
	session_start();
	require "php/sql.php";
?>

<!DOCTYPE html>
<html lang="pl">
	<head>
		<meta charset="UTF-8"/>
		<link href="https://fonts.googleapis.com/css?family=Josefin+Sans|Titan+One" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<link rel="stylesheet" href="style/style.css"/>
		<script src="js/server.js"></script>
		<script src="js/main.js"></script>
		<title>Warcaby</title>
		<script>
			const server = new Server();
		</script>
	</head>

	<body>
		<header>Warcaby</header>
		<main class="shadowLV4">
			<?php include "html/nav.php" ?>
			<article>
				<section>
					<h1>Dostępni użytkownicy</h1>
					<div class="shadowLV2inset" id="playerList"></div>
				</section>
				<section>
					<h1>Zaproszenia</h1>
					<div class="shadowLV2inset" id="inviteList">
						Hello world
					</div>
				</section>
			</article>
			<div style="clear: both"></div>
			<footer>Kuba Janczak 2018 &copy;</footer>
		</main>
		
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