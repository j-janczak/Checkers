$(document).ready(() => {
	$('#login_button').click(() => {
		$('#login_window').fadeIn('fast');
		$('#black_background').fadeIn('fast');
	});
	
	$('#register_button').click(() => {
		$('#register_window').fadeIn('fast');
		$('#black_background').fadeIn('fast');
	});
	
	$('#login_window a').click(() => {
		$("#login_window").fadeOut('fast');
		$('#black_background').fadeOut('fast');
	});
	
	$('#register_window a').click(() => {
		$("#register_window").fadeOut('fast');
		$('#black_background').fadeOut('fast');
	});
	
	$('#register_window #register_submit').click(() => {check_register();});
	
	$(window).resize(() => {resize();});
	resize();
});

function check_register() {
	const login = $('#register_login').val();
	//const email = $('#register_email').val();
	const password = $('#register_password').val();
	const password_confirm = $('#register_confirm_password').val();
	
	const email_validate = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

	var error = "";
	
	if (password != password_confirm) error = "Hasła nie są takie same";
	if (password.replace(/^\s+|\s+$/g, "").length == 0) error = "Hasło nie może być puste";
	/*if (email.replace(/^\s+|\s+$/g, "").length == 0) error = "Email nie może być pusty";
	if (!email_validate.test(email.toLowerCase())) error = "Email nie jest prawidłowy";*/
	if (login.replace(/^\s+|\s+$/g, "").length == 0) error = "Login nie może być pusty";
	if (grecaptcha.getResponse() == "") error = "Musisz potwierdzić że nie jesteś robotem ";
	
	if (error != "") {
		$('#register_error').css({display: 'block'});
		$('#register_error').html(error);
	}
	else {
		$('#register_error').css({display: 'none'});
		$('#register_form').submit();
	}
}

function resize() {
	const winWidth = $(window).width();
	const winHeight = $(window).height();
	const window_left = $(window).width() / 2 - $('#login_window').width() / 2;
	const succes_left = $(window).width() / 2 - 400 / 2;
	
	$('#login_window').css({top: '100px', left: window_left});
	$('#register_window').css({top: '100px', left: window_left});
	$('#msg_box').css({top: '0px', left: succes_left});
	
	$('#black_background').css({width: winWidth, height: winHeight});
}

class MsgBox {
	static show(msg, timeOut, color) {
		$('#msg_box').html(msg);
		$('#msg_box').css({'background-color': color});
		$('#msg_box').slideDown('fast');
		MsgBox.slideUp(timeOut);
	}

	static slideUp(timeOut) {
		setTimeout(() => { 
			$('#msg_box').slideUp('slow');
		}, timeOut);
	}
	
	static slideUpNow() {
		$('#msg_box').slideUp('slow');
	}
	
	static COLOR_GREEN() {return '#43A047'}
	static COLOR_RED() {return '#B71C1C'}
}