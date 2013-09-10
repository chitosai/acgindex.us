<?php

require_once('accounts.php');
require_once('MySQL.php');
require_once('utility.php');

/*
 * 日志系统
 * 
 */
class LOG {
    /*
     * 记录一次请求
     * @status 出现错误时如参数不正确等为ERROR，正常时一律返回OK
     * @value 资源地址，没找到资源时为-1
     */
    static function add( $bgmid, $epid, $source, $value ) {
        # 增加总点击量统计
        if( $value == '' || $value == '-1' ) {
            $status = 0;
            self::incr(false);
        } else {
            $status = 1;
            self::incr(true);
        }

        # 记录这次请求
        $db = new mysql();
        $db->execute("UPDATE log SET `count`=`count`+1 WHERE `bgm` = {$bgmid} AND `epid` = {$epid} AND `source` = '{$source}' AND `status` = {$status}");
        $r = $db->get_affected_rows();

        # update影响行数为0表示尚没有这条记录，插入之
        if( !$r ) {
            $db->insert('log', array(
                'bgm'    => $bgmid, 
                'epid'   => $epid,
                'source' => $source,
                'status' => $status
            ));
        }
    }


    /*
     * 增加点击量接口
     * 
     */
    static function incr( $is_found ) {
        # 增加总点击量
        self::_incr_(CACHE_STATISTICS_REQUEST_COUNT);
        # 找到/未找到资源的统计量
        if( $is_found ) 
            self::_incr_(CACHE_STATISTICS_FOUND_COUNT);
        else 
            self::_incr_(CACHE_STATISTICS_NOT_FOUND_COUNT);
    }

    /*
     * 真 · 增加点击量
     * 
     */
    static function _incr_( $key ) {
        $cache = new CACHE();
        # 增加缓存量
        $tmp = $cache->incr($key);
        if(!$tmp) $cache->set($key, 1, 0, CACHE_STATISTICS_EXPIRE);
        
        # 缓存达到阈值就写入mysql
        if( $tmp >= CACHE_STATISTICS_REQUEST_COUNT_MAX ) {
            $cache->delete($key);
            
            $db = new mysql();
            $r = $db->execute("UPDATE `statistics` SET `value`=`value`+".CACHE_STATISTICS_REQUEST_COUNT_MAX." WHERE `key`='{$key}'");
        }
        unset($cache);
    }
}