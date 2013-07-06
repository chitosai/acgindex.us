<?php
	if( $message ) {
		?>
		<p id="message">
			<?php echo $message; ?>
		</p>
		<?php
	}
?>
<input type="password" id="password">
<button id="login" onclick="do_login();">登录</button>
<script src="crypto.sha256.js"></script>
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