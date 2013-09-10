<?php

require_once('accounts.php');
require_once('MySQL.php');

class statistics {
    static function request_incr() {
        $cache = new CACHE();

        # 缓存自增
        $tmp = $cache->incr(CACHE_STATISTICS_REQUEST_COUNT);
        if(!$tmp) $cache->set(CACHE_STATISTICS_REQUEST_COUNT, 1, 0, CACHE_STATISTICS_EXPIRE);
        
        # 缓存达到阈值就写入mysql
        if( $tmp >= CACHE_STATISTICS_REQUEST_COUNT_MAX ) {
            $cache->delete(CACHE_STATISTICS_REQUEST_COUNT);
            
            $db = new mysql();
            $r = $db->execute('UPDATE `statistics` SET `value`=`value`+'.CACHE_STATISTICS_REQUEST_COUNT_MAX.' WHERE `key`=\'request_total\'');
        }
    }
}