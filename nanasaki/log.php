<?php if(!$login_ok) die(); ?>

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
			$file = preg_replace('/\[\d{2}:\d{2}:\d{2}\] (?:update bili resources|END)[\r\n]+/', '', $file);
			// 高亮特殊状态
			$file = preg_replace('/(\[\d{2}:\d{2}:\d{2}\] .+? NOT FOUND IN DATABASE !!!)/', '<strong>$1</strong>', $file);
			$file = preg_replace('/(\[\d{2}:\d{2}:\d{2}\] EP DATA OF .+? NOT EXISTS !!!)/', '<strong>$1</strong>', $file);
			// 去掉每次运行时的附加提示
			$file = preg_replace('/=+[\r\n]+/', '', $file);
			// 换行符替换为<br>
			$file = preg_replace('/[\r\n]+/', '<br>', $file);
			echo $file;
		} else
			echo "<br><br>############# {$filename} 不存在！！！";
	}
?>
</div>