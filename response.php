<?php

include('tsukasa/utility.php');
include('tsukasa/statistics.php');

function get_ep_by_bgmid( $bgmid, $epid, $source ) {
	# 先处理输入
	$bgmid = intval($bgmid);
	$epid = floatval($epid);

	if( !$bgmid || !$epid ) exit('-10');

	global $SOURCE_LIST;
	if( !in_array( $source, $SOURCE_LIST ) ) exit('-10');

	# 检查memcached
	$mc = new MC();
	$mc_key = sprintf(MC_KEY, $bgmid, $epid, $source);
	$r = $mc->get( $mc_key );
	if( $r ) {
		# 增加统计
		statistics::request_incr();
		echo $r;
		return true;
	}

	# BT资源只使用memcache缓存，如果memcache里没有数据就重新抓
	if( $source == 'bt' ) {
        require('tsukasa/bt.php');
        echo BT::update( $bgmid, $epid );
		return true;
	}

	# 没有就查sql
	$db = new mysql();

	# 先根据bgmid取到entryid
	$eid = $db->get('entry', 'id', 'bgm=' . $bgmid);
	if( !$eid ) exit('-20');

	# 然后根据eid和epid取地址
	$return_url = $db->get('ep', 'bili', 'eid=' . $eid . ' AND epid=' . $epid);
	if( !$return_url ) exit('-30');

	# 存进memcache
	if( $return_url != '' || $return_url != '-1')
		$mc->set( $mc_key, $return_url, 0, MC_EXIST_EXPIRE );
	else
		$mc->set( $mc_key, $return_url, 0, MC_NOT_EXIST_EXPIRE );

	# 增加统计
	statistics::request_incr();

	echo $return_url;
	return true;
}

# 响应输入
if( isset($_GET['b']) && isset($_GET['e']) && isset($_GET['source']) ) {
	get_ep_by_bgmid( $_GET['b'], $_GET['e'], $_GET['source'] );
} else {
	echo 'ACGINDEX CORE -HARUKA-';
}