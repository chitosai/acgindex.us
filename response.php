<?php

require_once('tsukasa/data.php');
require_once('tsukasa/log.php');

function get_ep_by_bgmid( $bgmid, $epid, $source ) {
	global $SOURCE_LIST;

	# 先验证参数
	$bgmid = intval($bgmid);
	$epid = floatval($epid);

	if( !$bgmid || !$epid || !in_array( $source, $SOURCE_LIST ) ) {
		USER::error('-10');
		return false;
	}

    # 判断两次请求是否间隔过短
    if( !USER::valid() ) {
    	USER::error('-20');
    	return false;
    }

	# 检查缓存
	GET::set_cache_key( sprintf(CACHE_KEY, $bgmid, $epid, $source) );
	$value = GET::cache( $bgmid, $epid, $source );
	# 有缓存的话直接返回缓存值
	if( $value ) {
		USER::send( $value, array('from'=>'cache') );
        LOG::add( $bgmid, $epid, $source, $value );
		return true;
	}

	# BT资源只储存在缓存中，如果缓存里没有数据就重新抓
	if( $source == 'bt' ) {
        $value = GET::bt( $bgmid, $epid );
        USER::send( $value, array('from'=>'ktxp') );
        LOG::add( $bgmid, $epid, $source, $value );
		return true;
	}

	# 其他资源去查数据库
	else {
		$value = GET::db( $bgmid, $epid, $source );
		USER::send( $value, array('from'=>'db') );
        LOG::add( $bgmid, $epid, $source, $value );
		return true;
	}

}

# 响应输入
if( isset($_GET['b']) && isset($_GET['e']) && isset($_GET['source']) ) {
	get_ep_by_bgmid( $_GET['b'], $_GET['e'], $_GET['source'] );
} else {
	echo 'ACGINDEX CORE -HARUKA-';
}