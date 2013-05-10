<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>七咲　逢</title>
	<script src="crypto.sha256.js"></script>
</head>
<body>
	<?php
		if( $message ) {
			?>
			<p id="message">
				<?php echo $message; ?>
			</p>
			<?php
		}
	?>
	<input type="text" id="password">
	<button id="login" onclick="do_login();">登录</button>
	<script>
		function setCookie(c_name, value, expiredays) {
		    var exdate = new Date();
		    expiredays = expiredays ? expiredays : 7;
		    console.log(expiredays)
		    exdate.setDate( exdate.getDate() + expiredays )
		    document.cookie = c_name + "=" + escape(value) 
		        + ( (expiredays == null) ? "" : ";expires=" + exdate.toGMTString() );
		}
		function do_login() {
			var password = document.querySelector('#password').value;
			var time = new Date().getTime();
			var auth = CryptoJS.SHA256( password + '^^^' + time ).toString();
			setCookie('time', time);
			setCookie('auth', auth);
		}
	</script>
</body>
</html>