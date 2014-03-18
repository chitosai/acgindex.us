<?php

class Log {
    /*
     * 记录一次请求
     * @status 出现错误时如参数不正确等为ERROR，正常时一律返回OK
     * @value 资源地址，没找到资源时为-1
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