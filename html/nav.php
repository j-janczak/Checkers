<nav class="shadowLV2">
	<ul id="nav_games" class="nav_menu">
		<li><a href="#">Strona główna</a></li>
	</ul>
	<ul id="nav_profil" class="nav_menu">
		<?php
			if(isset($_SESSION['logged_in'])) {
				$user = $database->query("SELECT * FROM `users` WHERE `id` = ".$_SESSION['logged_in'])->fetch_assoc();
				echo '<li><a href="#">Moje konto - <u>'.$user['login'].'</u></a></li>
					<li><a href="php/account.php?action=logout">Wyloguj</a></li>';
			} else {
				echo '<li><a href="#" id="login_button">Zaloguj</a></li>
					<li><a href="#" id="register_button">Załóż konto</a></li>';
			}
		?>
	</ul>
	<div class="clear"></div>
</nav>