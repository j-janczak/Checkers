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
					
					<!--<label for="email">Email:</label><br>
					<input type="email" id="register_email" name="email" class="input shadowLV2"/><br>-->
					
					<label for="password">Hasło:</label><br>
					<input type="password" id="register_password" name="password" class="input shadowLV2"/><br>
					
					<label for="password_confirm">Potwierdź hasło:</label><br>
					<input type="password" id="register_confirm_password" name="password_confirm" class="input shadowLV2"/><br>
					
					<div id="register_recaptcha" class="g-recaptcha" data-sitekey="6LcnoXMUAAAAAN0cpwd4GropJqLUn_pYhFQvDkKf"></div>
					
					<button type="button" id="register_submit">Zarejestruj</button>
				</form>
			</div>
		</div>