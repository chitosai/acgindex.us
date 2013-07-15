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

		// 统计数据
		function statstices() {
		    require_once('../tsukasa/config.php');
		    require_once('../tsukasa/MySQL.php');
		?>
		    <div id="statistics">
		        <p>
		            <b>当前累计点击：</b>
		            <?php
		                function get_db_views() {
		                    $db = new mysql();
		                    $views_in_db = $db->query("SELECT `value` from `statistics` WHERE `key` = 'request_total'");
		                    if( $views_in_db ) 
		                        return $views_in_db[0]['statistics']['value'];
		                    else 
		                        return false;
		                }
		                function get_cached_views() {
		                    $mc = new MC();
		                    $views_in_mc = $mc->get(MC_STATISTICS_REQUEST_COUNT);
		                    return $views_in_mc;
		                    $views = $views_in_db + $views_in_mc;
		                    echo $views;
		                }
		                function get_total_views() {
		                    $views_in_db = get_db_views();
		                    if( !$views_in_db ) 
		                        return '无法读取统计数据';
		                    else 
		                        return $views_in_db + get_cached_views();
		                }
		                echo get_total_views();
		            ?>
		        </p>
		        <p>
		            <b>缓存点击量：</b>
		            <?php
		                echo get_cached_views();
		            ?>
		        </p>
		    </div>
		<?php
		}

		if( do_check_auth() === true ) {
			statstices();
			require_once('memcache.php');
			cache_manager();
			require_once('log.php');
			log_list();
		} else
			login();
	?>

</body>
</html>