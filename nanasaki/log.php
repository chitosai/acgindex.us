<?php 
require_once('auth.php');

function log_list() {
	check_auth();
?>
	
	<div id="log">
<?php
	for( $delta = 0; $delta <= 5; $delta++ ) {
		// filename
		$date = new DateTime();
		$date->modify('-' . $delta . ' day');
		$date = $date->format('Y-m-d');
		$filename = LOG_PATH . '/' . $date . '.log';

		// open it
		if( file_exists($filename) ) {
			echo '<h2>' . $date . '</h2>';
			$file = file_get_contents( $filename );

			// 去掉每次运行的开始/结束提示
			$file = preg_replace('/\[\d{2}:\d{2}:\d{2}\] (?:update bili resources|END)[\r\n]+/', '', $file);
			$file = preg_replace('/=+[\r\n]+/', '', $file);

			// 高亮
			$file = preg_replace('/(\[\d{2}:\d{2}:\d{2}\] \[NO ENTRY\]) name: ([^\r\n]+)/', 
								 '<b>$1</b> name: <span class="easy-select">$2</span>', $file);
			$file = preg_replace('/(\[\d{2}:\d{2}:\d{2}\] \[NO EP\]) id: (\d+) \| bid: (\d+) \| name: ([^\r\n]+)/', 
								 '<b>$1</b> id: <span class="easy-select">$2</span> | bid: <span class="easy-select">$3</span> | name: <span class="easy-select">$4</span>', $file);
			// 低亮
			$file = preg_replace('/(\[\d{2}:\d{2}:\d{2}\] \[skip\] [^\r\n]+)/', '<span class="less-important">$1</span>', $file);

			// 换行符替换为<br>
			$file = preg_replace('/[\r\n]+/', '<br>', $file);
			echo $file;
		} else
			echo "<br><br>############# {$filename} 不存在！！！";
	}
?>
	<script>
		// 使.easy-select单击即可选中所有文本
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
	</div>
<?php
}