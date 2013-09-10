<?php
require_once('accounts.php');
require_once('config.php');
require_once('MySQL.php');
require_once('utility.php');

/*
 * 数据中心负责查询收
 * @ 找到资源的情况下返回资源地址
 * @ 没找到的情况下返回-1
 */
class GET {
    # 缓存KEY
    static private $cache_key = 'CACHE_KEY';

    /*
     * 设置缓存KEY的值
     * 
     */
    static function set_cache_key( $key ) {
        self::$cache_key = $key;
    }

    /*
     * 在缓存中查找
     * 
     */
    static function cache( $bgmid, $epid, $source ) {
        $cache = new CACHE();
        $r = $cache->get( self::$cache_key );
        unset($cache);
        return $r;
    }

    /*
     * 从数据库中查找并缓存
     * 
     */
    static function db( $bgmid, $epid, $source ) {
        # 发起查询
        $db = new mysql();
        $data = $db->query(sprintf("SELECT `ep`.`%s` FROM `ep` LEFT JOIN `entry` ON `ep`.`eid` = `entry`.`id` AND `ep`.`epid` = %s WHERE `entry`.`bgm` = %s", $source, $epid, $bgmid));

        # 没有资源的情况
        if( !$data ) $r = '-1';
        # 找到资源的情况
        else $r = $data[0]['ep']['bili'];

        # 缓存
        $cache = new CACHE();
        if( $r != '' || $r != '-1')
            $cache->set( self::$cache_key, $r, 0, CACHE_EXIST_EXPIRE );
        else
            $cache->set( self::$cache_key, '-1', 0, CACHE_NOT_EXIST_EXPIRE );
        unset($cache);

        return $r;
    }

    /*
     * 抓取BT资源（目前是KTXP）并缓存
     * @ 找到资源的情况下返回资源地址
     * @ 没找到的情况下返回-1
     */
    static function bt( $bgmid, $epid ) {
        # 根据bgmid取中文名
        $db = new mysql();
        # 查找bgm收录的中文名及names表收录的别名
        $names = $db->query(sprintf('SELECT `entry`.`name_cn`, `names`.`real_name` FROM `entry` LEFT JOIN `names` ON `entry`.`id` = `names`.`eid` AND `names`.`source` = %s WHERE `entry`.`bgm` = %s', CACHE_BT_SOURCE_ID, $bgmid));
        if( !$names ) return '-1';
        # 如果有别名则选用别名，没有别名就直接用bgm的中文名
        $names = $names[0];
        if( $names['names']['real_name'] )
            $name = $names['names']['real_name'];
        else
            $name = $names['entry']['name_cn'];

        # 准备发送请求
        if( $epid < 10 ) $str_epid = '0' . $epid;
        else $str_epid = $epid;
        $url = sprintf(KTXP_SEARCH , urlencode( $name . ' ' . $str_epid ));

        # 获得返回的页面
        $html = file_get_contents($url);

        # 查找第一个下载链接，就是种子数最多的资源了
        $find = preg_match("/<a href=\"\/down\/[\w\d\/]+\.torrent\" class=\"quick-down cmbg\"><\/a><a href=\"([\w\d\/\.]+)\" target=\"_blank\">/", $html, $match);

        # 准备写入缓存
        $cache = new CACHE();

        # 找到结果
        if( $find ) {
            $cache->set( self::$cache_key, $match[1], 0, CACHE_BT_EXIST_EXPIRE );
            $r = $match[1];
        } else {
        # 没有结果
            $cache->set( self::$cache_key, '-1', 0, CACHE_BT_EXIST_EXPIRE );
            $r = '-1';
        }
        unset($cache);

        return $r;
    }
}