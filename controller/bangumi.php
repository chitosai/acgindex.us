<?

class Bangumi {
    public static function Get($params) {
        extract($params);

        # 检查参数是否合法
        API::valid($bgmid, $epid, $source);

        $bgmid = intval($bgmid);
        $epid  = intval($epid);

        # 把bgmid换成entryid
        $eid = self::get_entry_id_by_bangumi_id($bgmid);

        # 转发给API接口
        API::Get($eid, $epid, $source);
    }

    public static function get_entry_id_by_bangumi_id($bgmid) {
        $r = DB::query("SELECT `id` FROM `entry` WHERE `bgm` = ?", array($bgmid));
        if( !count($r) ) {
            API::error("Bangumi id: {$bgmid} 不存在");
        } else {
            return $r[0]['id'];
        }
    }
}