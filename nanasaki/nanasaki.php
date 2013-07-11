<!doctype html>
<html lang="cn">
<head>
	<meta charset="UTF-8">
	<title>愛してる</title>
	<link rel="stylesheet" href="nanasaki.css">
</head>
<body>
	<?php
		require_once('auth.php');

		if( do_check_auth() ) {
			require_once('memcache.php');
			cache_manager();
			require_once('log.php');
			log_list();
		} else
			login();
	?>

</body>
</html>