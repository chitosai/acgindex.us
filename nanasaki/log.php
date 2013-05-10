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
			$file = str_replace("\r\n", '<br>', $file);
			$file = str_replace("<br>========================================================", '', $file);
			$file = preg_replace('/\[\d{2}:\d{2}:\d{2}\] (?:update bili resources|END)<br>/', '', $file);
			echo $file;
		} else
			echo "<br><br>######### {$filename} 不存在！！！";
	}
?>
</div>