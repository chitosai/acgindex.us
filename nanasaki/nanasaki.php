<!doctype html>
<html lang="cn">
<head>
	<meta charset="UTF-8">
	<title>愛してる</title>
	<link rel="stylesheet" href="nanasaki.css">
</head>
<body>
	<?php
		include '../tsukasa/accounts.php';
		include '../tsukasa/config.php';

		function need_login( $message = null ) {
			include 'need_login.php';
		}

		function check_login() {
			if( !isset($_COOKIE['auth']) || !isset($_COOKIE['time']) ) 
				return false;

			$time = $_COOKIE['time'];
			$auth = $_COOKIE['auth'];

			// 确定cookie有效期
			if( (time() - $time) > ADMIN_COOKIE_EXPIRE )
				return 'COOKIE已过期';

			// 确认auth是否合法
			$_auth = hash('sha256', ADMIN_PASS . '^^^' . $time);
			if( $auth !== $_auth )
				return '密码错误';

			// 没问题了
			return true;
		}

		function admin() {
			$login_ok = true;
			include 'log.php';
		}


		if( ( $message = check_login() ) === true )
			admin();
		else if( gettype($message) === string )
			need_login($message);
		else
			need_login();
	?>
</body>
</html>