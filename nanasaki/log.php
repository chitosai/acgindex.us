<pre>
<?php

	for( $delta = 0; $delta <= 5; $delta++ ) {
		// filename
		$date = new DateTime();
		$date->modify('-' . $delta . ' day');
		$filename = LOG_PATH . '/' . $date->format('Y-m-d.\l\o\g');

		// open it
		if( file_exists($filename) )
			readfile( $filename );
		else
			echo "<br><br>######### {$filename} 不存在！！！";
	}
?>
</pre>