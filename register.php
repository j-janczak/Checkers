<?php
	session_start();
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
		<title>Załóż konto</title>
		<script>
			const server = new Server();
		</script>
	</head>

	<body>
		<header>Warcaby</header>
		<main class="shadowLV4">
			<nav class="shadowLV2">
				<ul id="nav_games" class="nav_menu">
					<li><a href="#">Szukaj Gry</a></li>
					<li><a href="#">Utwórz grę</a></li>
				</ul>
				<ul id="nav_profil" class="nav_menu">
					<?php
						if(isset($_SESSION['logged_in'])) {
							echo '<li><a href="#">Moje konto</a></li>
								  <li><a href="php/account.php?action=logout">Wyloguj</a></li>';
						} else {
							echo '<li><a href="#" id="login_button">Zaloguj</a></li>
								  <li><a href="#" id="register_button">Załóż konto</a></li>';
						}
					?>
				</ul>
				<div class="clear"></div>
			</nav>
			<article>
				<h1>Warcaby</h1>
				<div id="game_container">
					
				</div>
			</article>
			<footer>Kuba Janczak 2018 &copy;</footer>
		</main>
		<script>
			const game = new Game('#game_container');
		</script>
		
		<div id="login_window" class="window shadowLV4">
			<div class="window_title_bar shadowLV2">Zaloguj się <a href="#">X</a></div>
			<div class="window_container account_container">
				<form action="php/account.php?action=login" method="POST">
					<?php
						if(isset($_SESSION['login_error'])) {
							echo '<div class="error" style="display: block">'.$_SESSION['login_error'].'</div>';
							echo '<script>
									 $(document).ready(() => {
										$("#login_button").trigger( "click" );
									 });
								  </script>';
						}
					?>
					<label for="login">Login:</label><br>
					<input type="text" id="login_login" name="login" class="input shadowLV2"/><br>
					
					<label for="password">Hasło:</label><br>
					<input type="password" id="login_password" name="password" class="input shadowLV2"/><br>
					<input type="submit" value="Zaloguj"/>
				</form>
			</div>
		</div>
		
		<div id="register_window" class="window shadowLV4">
			<div class="window_title_bar shadowLV2">Zarejestruj się <a href="#">X</a></div>
			<div class="window_container account_container">
				<div id="register_error" class="error" style="display: none">error</div>
				
				<form id="register_form" action="php/account.php?action=register" method="POST">
					<?php
						if(isset($_SESSION['register_error'])) {
							echo '<div class="error" style="display: block">'.$_SESSION['register_error'].'</div>';
							echo '<script>
									 $(document).ready(() => {
										$("#register_button").trigger( "click" );
									 });
								  </script>';
						}
					?>
					<label for="login">Login:</label><br>
					<input type="text" id="register_login" name="login" class="input shadowLV2"/><br>
					
					<label for="email">Email:</label><br>
					<input type="email" id="register_email" name="email" class="input shadowLV2"/><br>
					
					<label for="password">Hasło:</label><br>
					<input type="password" id="register_password" name="password" class="input shadowLV2"/><br>
					
					<label for="password_confirm">Potwierdź hasło:</label><br>
					<input type="password" id="register_confirm_password" name="password_confirm" class="input shadowLV2"/><br>
					
					<div id="register_recaptcha" class="g-recaptcha" data-sitekey="6LcnoXMUAAAAAN0cpwd4GropJqLUn_pYhFQvDkKf"></div>
					
					<button type="button" id="register_submit">Zarejestruj</button>
				</form>
			</div>
		</div>
		
		<div id="msg_box" class="shadowLV3"></div>
	</body>
</html>

<?php
	unset($_SESSION['register_error']);
?>