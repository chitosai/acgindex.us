<?php

require_once('tsukasa/utility.php');
require_once('tsukasa/statistics.php');

function get_ep_by_bgmid( $bgmid, $epid, $source ) {
	global $SOURCE_LIST;

	# 先验证参数
	$bgmid = intval($bgmid);
	$epid = floatval($epid);

	if( !$bgmid || !$epid || !in_array( $source, $SOURCE_LIST ) ) 
		return USER::error('-10');

	# 检查memcache
	$mc = new MC();
	$mc_key = sprintf(MC_KEY, $bgmid, $epid, $source);
	$r = $mc->get( $mc_key );
	# memcache有缓存的话直接返回缓存值
	if( $r ) {
		return USER::send( $r, array('from'=>'cache') );
	}

	# BT资源只使用memcache缓存，如果memcache里没有数据就重新抓
	if( $source == 'bt' ) {
        require_once('tsukasa/bt.php');
        $r = BT::update( $bgmid, $epid );
        return USER::send( $r, array('from'=>'fetch') );
	}

	# 没有就查sql
	$db = new mysql();
	$data = $db->query(sprintf("SELECT `ep`.`bili` FROM `ep` LEFT JOIN `entry` ON `ep`.`eid` = `entry`.`id` AND `ep`.`epid` = %s WHERE `entry`.`bgm` = %s", $epid, $bgmid));

	# 没有资源的情况
	if( !$data ) $r = '-1';
	# 找到资源的情况
	else $r = $data[0]['ep']['bili'];

	# 存进memcache
	if( $r != '' || $r != '-1')
		$mc->set( $mc_key, $r, 0, MC_EXIST_EXPIRE );
	else
		$mc->set( $mc_key, '-1', 0, MC_NOT_EXIST_EXPIRE );

	return USER::send( $r, array('from'=>'db') );
}

# 响应输入
if( isset($_GET['b']) && isset($_GET['e']) && isset($_GET['source']) ) {
	get_ep_by_bgmid( $_GET['b'], $_GET['e'], $_GET['source'] );
} else {
	echo 'ACGINDEX CORE -HARUKA-';
}