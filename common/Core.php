<?

class Core {
    /**
     * 检查用户请求是否合法
     * @param [integer] $eid    [entry id]
     * @param [integer] $epid   [ep id]
     * @param [string]  $source [bili/bt/etc..]
     */
    static function valid($eid, $epid, $source) {
        # 检查访问是否过于频繁
        $prevent_repeat_key = sprintf(CACHE_REPEAT_DELAY_KEY, $_SERVER['REMOTE_ADDR']);
        if( Cache::has($prevent_repeat_key) ) {
            return '请求速度过快';
        } else {
            Cache::set($prevent_repeat_key, 1, CACHE_REPEAT_DELAY_TIME);
        }

        # 验证参数
        global $SOURCE_LIST;
        $eid = intval($eid);
        $epid = floatval($epid);

        if( !$eid || !$epid || !in_array($source, $SOURCE_LIST) ) {
            return '请求的参数非法';
        }

        # NO PROBLEM
        return true;
    }

    /**
     * 把$source字符串转为$source id
     * @param [string] $source [bili/bt/etc..]
     */
    static function get_source_id($source) {
        global $SOURCE_LIST;
        return $SOURCE_LIST[$source];
    }
}