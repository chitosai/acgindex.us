<?php

class Log {
    /*
     * 记录一次请求
     * 
     */
    static function add( $eid, $epid, $source, $value ) {
        # 剔除监控宝的监控流量
        if( $eid == 64690 && $epid == 1 && $source == 'bili' )
            return false;

        # 添加记录
        DB::insert('log', array(
            'eid' => $eid,
            'epid' => $epid,
            'source' => CORE::get_source_id($source),
            'found' => ( $value == '' || $value == '-1' ) ? false : true,
            'ip' => ip2long($_SERVER['REMOTE_ADDR'])
        ));
    }
}