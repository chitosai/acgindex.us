<!doctype html>
<html lang="cn">
<head>
    <? include '_head.php'; ?>
</head>
<body>
    <div class="content error">
        <h1><?=$title;?></h1>
        <p><?=$message;?></p>
        <? if( isset($show_login) ): ?>
            <input type="password" id="password"><!--
            --><a id="login" href="<?=$redirect_url;?>">登录</a>
        <script src="<?=STATIC_PATH;?>/js/crypto.sha256.js"></script>
        <script>
            document.querySelector('#login').addEventListener('click', do_login);
            document.querySelector('#password').focus();
            function setCookie(c_name, value, expiredays) {
                var exdate = new Date();
                expiredays = expiredays ? expiredays : 7;
                exdate.setDate( exdate.getDate() + expiredays )
                document.cookie = c_name + "=" + escape(value) 
                    + ( (expiredays == null) ? "" : ";expires=" + exdate.toGMTString() );
            }
            function do_login() {
                var password = document.querySelector('#password').value;
                var timestamp = new Date().getTime();
                var hash = CryptoJS.SHA256( password + '^^^' + timestamp ).toString();
                setCookie('timestamp', timestamp);
                setCookie('hash', hash);
            }
        </script>
        <? endif; ?>
    </div>
    
    <? include '_footer.php'; ?>
</body>
</html>