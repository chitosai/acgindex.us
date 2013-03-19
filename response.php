<?php

include('tsukasa/utility.php');
include('tsukasa/MySQL.php');

function get_ep_by_bgmid( $bgmid, $epid, $source ) {
	# 先处理输入
	$bgmid = intval($bgmid);
	$epid = intval($epid);

	if( !$bgmid || !$epid ) exit('-10');

	global $SOURCE_LIST;
	if( !in_array( $source, $SOURCE_LIST ) ) exit('-10');

	# 先检查memcached
	$mc_key = $bgmid . ':' . $epid . ':' . $source;
	$mc = new MC();
	$r = $mc->get( $mc_key );
	if( $r ) {
		echo $r;
		return;
	}

	# 没有就查sql
	$db = new mysql();

	# 先根据bgmid取到entryid
	$eid = $db->get('entry', 'id', 'bgm='.$bgmid);
	if( !$eid ) exit('-20');

	# 然后根据eid和epid取地址
	$return_url = $db->get('ep', 'bili', 'eid='.$eid.' AND epid='.$epid);
	if( !$eid ) exit('-30');

	# 存进memcache
	if( $return_url != '' || $return_url != '-1')
		$mc->set( $mc_key, $return_url, MC_EXIST_EXPIRE );
	else
		$mc->set( $mc_key, $return_url, MC_NOT_EXIST_EXPIRE );

	echo $return_url;
	return;
}

# 响应输入
if( isset($_GET['b']) && isset($_GET['e']) && isset($_GET['source']) ) {
	get_ep_by_bgmid( $_GET['b'], $_GET['e'], $_GET['source'] );
} else {
	echo 'ACGINDEX CORE -HARUKA-';
}