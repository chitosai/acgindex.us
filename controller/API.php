<?
/**
 * 以json形式返回数据
 * 
 */
class API {
    /**
     * 对外接口 TYPE.I 
     * @param [integer] $eid    [entry id]
     * @param [integer] $epid   [ep id]
     * @param [string]  $source [bili/bt/etc..]
     */
    static function Get($eid, $epid, $source) {
        # 发起查询
        $value = Data::Get($eid, $epid, $source);
        # 输出
        self::success($value);
    }

    /**
     * 检查用户请求是否合法
     * @param [integer] $eid    [entry id]
     * @param [integer] $epid   [ep id]
     * @param [string]  $source [bili/bt/etc..]
     */
    static function valid($eid, $epid, $source) {
        $valid = Core::valid($eid, $epid, $source);
        if( gettype($valid) == 'string' ) {
            self::error($valid);
        }
    }

    /**
     * 向用户返回一个“完成”的json
     */
    static function success($value, $extra = null) {
        self::send('OK', $value, $extra);
    }

    /**
     * 向用户返回一个“错误”的json
     */
    static function error($value, $extra = null) {
        self::send('ERROR', $value, $extra);
    }

    /**
     * 输出json格式的返回值并终止程序
     */
    static function send($status, $value, $extra = null) {
        # 包装基本参数
        $return = array(
            'status' => $status,
            'value'  => $value,
        );

        # 如果有额外参数就带上
        if( $extra ) 
            $return = array_merge($return, $extra);

        # 搞成json送出并终止程序
        die(json_encode($return));
    }
}