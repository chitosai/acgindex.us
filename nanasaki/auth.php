<?php
require_once('../tsukasa/utility.php');

// 登录
function login( $message = null ) {
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
<?php
}


// 检查权限
function do_check_auth() {
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

// 确认权限
function check_auth() {
	if( ( $message = do_check_auth() ) !== true ) {
		die($message);
	} else 
		return true;
}