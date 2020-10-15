<?php
	session_start();
	require "sql.php";
	
	function success_msg($msg) {
		$_SESSION['success_msg'] = $msg;
		header('Location: '.indexLocation);
		exit();
	}
	
	function login_exit_and_error($error_msg){
		$_SESSION['login_error'] = $error_msg;
		header('Location: '.indexLocation);
		exit();
	}
	
	function register_exit_and_error($error_msg){
		$_SESSION['register_error'] = $error_msg;
		header('Location: '.indexLocation);
		exit();
	}

	if (!isset($_GET['action'])) {
		header('Location: '.indexLocation);
		exit();
	} else if ($_GET['action'] == "logout") {
		unset($_SESSION['logged_in']);
		header('Location: '.indexLocation);
		exit();
	} else if ($_GET['action'] == "login") {
		if ((!isset($_POST['login'])) || (!isset($_POST['password']))) {
			header('Location: '.indexLocation);
			exit();
		}
		
		$login = htmlentities($_POST['login'], ENT_QUOTES, "UTF-8");
		$password = md5($_POST['password']);
		
		$result = $database->query(
			sprintf("SELECT * FROM users WHERE login='%s' AND password='%s'",
			mysqli_real_escape_string($database,$login), $password));
			
		$users_count = $result->num_rows;
		if($users_count == 1)
		{
			$user_data = $result->fetch_assoc();
			$_SESSION['logged_in'] = $user_data['id'];
			$_SESSION['succes_msg'] = "Zalogowano pomyślnie";
			$database->query("UPDATE `users` SET `last_seen` = '".time()."' WHERE `users`.`id` = ".$user_data['id'].";");
			header('Location: '.indexLocation);
			exit();
		} else {
			login_exit_and_error("Błedny login lub hasło");
		}
	} else if ($_GET['action'] == "register") {
		//if ((!isset($_POST['login'])) || (!isset($_POST['email'])) || (!isset($_POST['password'])) || (!isset($_POST['password_confirm']))) {
		if ((!isset($_POST['login'])) || (!isset($_POST['password'])) || (!isset($_POST['password_confirm']))) {
			register_exit_and_error("Nie podano wszyskich danych");
		}
		
		//if (($_POST['login']=="") || ($_POST['email']=="") || ($_POST['password']=="") || ($_POST['password_confirm']=="")){
		if (($_POST['login']=="") || ($_POST['password']=="") || ($_POST['password_confirm']=="")){
			register_exit_and_error("Nie podano wszyskich danych");
		}
		
		$reCaptchaApi = 'https://www.google.com/recaptcha/api/siteverify';
		$post = array('secret' => '', 'response' => $_POST['g-recaptcha-response']);
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($post)
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($reCaptchaApi, false, $context);
		$result_captcha = json_decode($result);
		echo $result_captcha->{'success'};
		//if(!$result_captcha->{'success'}) register_exit_and_error("Bład weryfikacji Captchy");

		$login = htmlentities($_POST['login'], ENT_QUOTES, "UTF-8");
		//$email = htmlentities($_POST['email'], ENT_QUOTES, "UTF-8");
		$email = "";
		$password = md5($_POST['password']);
		$password_confirm = md5($_POST['password_confirm']);
		$data = date("Y-m-d H:i:s");
		
		if ($password != $password_confirm) {
			register_exit_and_error("Hasła nie są identyczne");
		}
		
		$database->query(
			sprintf("INSERT INTO `users` (`id`, `login`, `email`, `password`, `register_date`, `last_seen`) VALUES (NULL, '%s', '%s', '%s', '$data', '".(time()-5)."');",
			mysqli_real_escape_string($database,$login),
			mysqli_real_escape_string($database,$email),
			mysqli_real_escape_string($database,$password)));
			
		$_SESSION['succes_msg'] = "Konto utworzone poprawnie!";
		header('Location: '.indexLocation);
		exit();
	}
?>
