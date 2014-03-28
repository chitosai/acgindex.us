<? if( !defined('BASE_PATH') ) exit(); ?>
<!doctype html>
<html lang="cn">
<head>
    <? include TEMPLATE_COMMON_PATH . 'head.php'; ?>
</head>
<body>
    <? include TEMPLATE_COMMON_PATH . 'header.php'; ?>

    <div class="content" id="log">
    <? foreach( $logs as $date => $content ): ?>
        <h1><?=$date;?></h1>
        <p class="log1d"><?=$content;?></p>
    <? endforeach; ?>
    </div>
    <script>
        // .easy-select单击即可选中所有文本
        function selectText(event) {
            var doc = document;
            var text = event.target;

            if (doc.body.createTextRange) { // ms
                var range = doc.body.createTextRange();
                range.moveToElementText(text);
                range.select();
            } else if (window.getSelection) { // moz, opera, webkit
                var selection = window.getSelection();            
                var range = doc.createRange();
                range.selectNodeContents(text);
                selection.removeAllRanges();
                selection.addRange(range);
            }
        }
        var nodes = document.querySelectorAll('.easy-select');
        for( var i = 0; i < nodes.length; i++ ) {
            nodes[i].addEventListener('click', selectText);
        }
    </script>

    <? include TEMPLATE_COMMON_PATH . 'footer.php'; ?>
</body>
</html>