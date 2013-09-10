<?php

require_once('accounts.php');
require_once('MySQL.php');

class statistics {
    static function request_incr() {
        $mc = new MC();

        # 缓存自增
        $tmp = $mc->incr(MC_STATISTICS_REQUEST_COUNT);
        if(!$tmp) $mc->set(MC_STATISTICS_REQUEST_COUNT, 1, 0, MC_STATISTICS_EXPIRE);
        
        # 缓存达到阈值就写入mysql
        if( $tmp >= MC_STATISTICS_REQUEST_COUNT_MAX ) {
            $mc->delete(MC_STATISTICS_REQUEST_COUNT);
            
            $db = new mysql();
            $r = $db->execute('UPDATE `statistics` SET `value`=`value`+'.MC_STATISTICS_REQUEST_COUNT_MAX.' WHERE `key`=\'request_total\'');
        }
    }
}