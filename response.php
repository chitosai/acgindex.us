<?php

require_once('tsukasa/data.php');
require_once('tsukasa/statistics.php');

function get_ep_by_bgmid( $bgmid, $epid, $source ) {
	global $SOURCE_LIST;

	# 先验证参数
	$bgmid = intval($bgmid);
	$epid = floatval($epid);

	if( !$bgmid || !$epid || !in_array( $source, $SOURCE_LIST ) ) 
		return USER::error('-10');


	# 检查memcache
	GET::setCacheKey( sprintf(MC_KEY, $bgmid, $epid, $source) );
	$r = GET::cache( $bgmid, $epid, $source );
	# memcache有缓存的话直接返回缓存值
	if( $r ) {
		return USER::send( $r, array('from'=>'cache') );
	}

	# BT资源只使用memcache缓存，如果memcache里没有数据就重新抓
	if( $source == 'bt' ) {
        $r = GET::bt( $bgmid, $epid );
        return USER::send( $r, array('from'=>'ktxp') );
	}
	# 其他资源去查数据库
	else {
		$r = GET::db( $bgmid, $epid, $source );
		return USER::send( $r, array('from'=>'db') );
	}

}

# 响应输入
if( isset($_GET['b']) && isset($_GET['e']) && isset($_GET['source']) ) {
	get_ep_by_bgmid( $_GET['b'], $_GET['e'], $_GET['source'] );
} else {
	echo 'ACGINDEX CORE -HARUKA-';
}